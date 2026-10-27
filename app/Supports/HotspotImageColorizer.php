<?php

namespace App\Supports;

use App\Models\Hotspot;
use App\Models\InvestmentProductStatus;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class HotspotImageColorizer
{
    private const MAX_BYTES     = 5 * 1024 * 1024; // ảnh tải về tối đa 5MB
    private const MAX_PIXELS    = 4_000_000;       // khoảng 2000x2000
    private const SAT_THRESHOLD = 0.15;            // pixel xám/trắng (viền) giữ nguyên
    private const SOLID_FILL = true;  // true: vùng màu thành đục hoàn toàn; false: giữ độ trong suốt gốc
    private const CURRENT = 'current.png'; // ảnh đang áp dụng ("trước")
    private const PENDING = 'pending.png'; // ảnh của bản chờ duyệt ("sau")

    /**
     * Đổi màu ảnh hotspot theo trạng thái hiện tại của lô đất.
     * - Bản gốc (is_draft = 0): ghi current.png, xóa mọi file thừa.
     * - Bản nháp (is_draft = 1): ghi pending.png, current.png giữ nguyên để so sánh.
     *
     * @param Hotspot|int|iterable<Hotspot|int> $hotspots 1 hoặc nhiều hotspot (model hoặc id)
     * @param bool $updateDb  true: ghi url mới vào cột hotspot.url (chỉ cột này, không ghi log)
     * @param bool $verifySsl false: bỏ kiểm tra SSL khi tải ảnh nguồn (chỉ dùng khi lỗi chứng chỉ)
     * @return array<int, array{ok: bool, changed: bool, url: ?string, message: string}>
     */
    public static function apply($hotspots, bool $updateDb = true, bool $verifySsl = true): array
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('Máy chủ chưa bật extension GD của PHP');
        }

        $items = ($hotspots instanceof Hotspot || !is_iterable($hotspots))
            ? collect([$hotspots])
            : collect($hotspots);

        $models = $items->filter(fn ($i) => $i instanceof Hotspot);
        $ids    = $items->reject(fn ($i) => $i instanceof Hotspot)->filter(fn ($i) => is_numeric($i))->all();
        if (!empty($ids)) {
            $models = $models->merge(Hotspot::whereIn('id', $ids)->get());
        }
        $models = $models->unique('id')->values();

        $statuses = InvestmentProductStatus::whereIn(
            'id',
            $models->pluck('investment_product_statuses_id')->filter()->unique()
        )->get()->keyBy('id');
        $codes = Project::whereIn('id', $models->pluck('vrtour_id')->unique())->pluck('vrtour_code', 'id');

        $results = [];
        foreach ($models as $h) {
            try {
                $results[$h->id] = self::processOne($h, $statuses, $codes, $updateDb, $verifySsl);
            } catch (Throwable $e) {
                report($e);
                $results[$h->id] = self::fail($h, $e->getMessage());
            }
        }

        return $results;
    }

    /** Xóa ảnh chờ duyệt (gọi khi từ chối hoặc hủy bản nháp). */
    public static function discardPending($hotspot): void
    {
        $h = $hotspot instanceof Hotspot ? $hotspot : Hotspot::find($hotspot);
        if (!$h || !$h->potision) {
            return;
        }
        $code = Project::where('id', $h->vrtour_id)->value('vrtour_code');
        if (!$code) {
            return;
        }

        @unlink(public_path(
            'vrtour/' . self::safe($code) . '/image/' . self::safe($h->potision) . '/' . self::PENDING
        ));
    }

    private static function processOne(Hotspot $h, $statuses, $codes, bool $updateDb, bool $verifySsl): array
    {
        if ((int) $h->type !== 2) {
            return self::fail($h, 'Không phải lô đất');
        }
        $vrtourCode = $codes[$h->vrtour_id] ?? null;
        if (!$vrtourCode || !$h->potision) {
            return self::fail($h, 'Thiếu mã dự án (vrtour_code) hoặc mã lô');
        }

        $codeSafe = self::safe($vrtourCode);
        $lotSafe  = self::safe($h->potision);
        $dir      = public_path("vrtour/{$codeSafe}/image/{$lotSafe}");
        File::ensureDirectoryExists($dir, 0755, true);

        $status = $statuses[$h->investment_product_statuses_id] ?? null;
        $color  = self::parseColor($status?->color_code);

        return $h->is_draft
            ? self::processDraft($h, $color, $dir, $codeSafe, $lotSafe, $updateDb, $verifySsl)
            : self::processBase($h, $color, $dir, $codeSafe, $lotSafe, $updateDb, $verifySsl);
    }

    /** Bản gốc: ghi current.png, dọn mọi file khác (kể cả file của phiên bản cũ). */
    private static function processBase(Hotspot $h, ?array $color, string $dir, string $codeSafe, string $lotSafe, bool $updateDb, bool $verifySsl): array
    {
        if (!$color) {
            // Chưa có trạng thái: không có màu để tô, giữ nguyên ảnh hiện tại
            return ['ok' => true, 'changed' => false, 'url' => $h->url, 'message' => 'Chưa có trạng thái, giữ nguyên ảnh'];
        }

        $img = self::load(self::loadSource($dir, $h, null, $verifySsl));
        self::recolor($img, $color);
        $target = $dir . DIRECTORY_SEPARATOR . self::CURRENT;
        file_put_contents($target, self::encode($img), LOCK_EX);

        // Chỉ giữ pending.png khi lô còn bản nháp đang chờ duyệt
        $keep = [self::CURRENT];
        if (Hotspot::where('parent_id', $h->id)->where('is_draft', 1)->exists()) {
            $keep[] = self::PENDING;
        }
        self::cleanup($dir, $keep);

        return self::finish($h, $codeSafe, $lotSafe, self::CURRENT, $target, $updateDb);
    }

    /** Bản nháp: ghi pending.png, giữ nguyên current.png để so sánh. */
    private static function processDraft(Hotspot $h, ?array $color, string $dir, string $codeSafe, string $lotSafe, bool $updateDb, bool $verifySsl): array
    {
        $parent = $h->parent_id ? Hotspot::find($h->parent_id) : null;
        $target = $dir . DIRECTORY_SEPARATOR . self::PENDING;

        // Trạng thái không đổi so với bản gốc: trước và sau là một ảnh, không cần file mới
        if ($parent && (int) $parent->investment_product_statuses_id === (int) $h->investment_product_statuses_id) {
            @unlink($target);

            return self::setUrl($h, $parent->url, $updateDb);
        }

        if (!$color) {
            return ['ok' => true, 'changed' => false, 'url' => $h->url, 'message' => 'Chưa có trạng thái, giữ nguyên ảnh'];
        }

        $img = self::load(self::loadSource($dir, $h, $parent, $verifySsl));
        self::recolor($img, $color['hue']);
        file_put_contents($target, self::encode($img), LOCK_EX);

        return self::finish($h, $codeSafe, $lotSafe, self::PENDING, $target, $updateDb);
    }

    /** Lấy ảnh nguồn để tô: file đã có trên máy chủ trước, không có thì tải từ url bên ngoài. */
    private static function loadSource(string $dir, Hotspot $h, ?Hotspot $parent, bool $verifySsl): string
    {
        // original.png là file của phiên bản trước, vẫn đọc được để chuyển đổi dữ liệu cũ
        foreach ([self::CURRENT, self::PENDING, 'original.png'] as $name) {
            $path = $dir . DIRECTORY_SEPARATOR . $name;
            if (is_file($path)) {
                return file_get_contents($path);
            }
        }

        foreach ([$h->url, $parent?->url] as $url) {
            // Không bao giờ tải lại chính ảnh do hệ thống tạo ra
            if (!$url || str_contains($url, 'hotspot-image/')) {
                continue;
            }
            $src = str_starts_with($url, '/') ? url($url) : $url;
            if (!preg_match('#^https?://#i', $src)) {
                continue;
            }

            $response = Http::timeout(30)
                ->when(!$verifySsl, fn ($r) => $r->withoutVerifying())
                ->get($src);
            if (!$response->successful()) {
                throw new RuntimeException('Không tải được ảnh gốc, HTTP ' . $response->status());
            }
            if (strlen($response->body()) > self::MAX_BYTES) {
                throw new RuntimeException('Ảnh gốc quá lớn');
            }

            return $response->body();
        }

        throw new RuntimeException('Không tìm thấy ảnh nguồn của lô đất');
    }

    private static function finish(Hotspot $h, string $codeSafe, string $lotSafe, string $file, string $target, bool $updateDb): array
    {
        // Tên file cố định nên dùng mã băm nội dung làm ?v= để trình duyệt luôn lấy đúng ảnh mới
        $url = route('hotspot_image', [
            'code' => $codeSafe,
            'lot'  => $lotSafe,
            'file' => $file,
        ]) . '?v=' . substr(md5_file($target), 0, 10);

        return self::setUrl($h, $url, $updateDb);
    }

    private static function setUrl(Hotspot $h, ?string $url, bool $updateDb): array
    {
        $changed = $h->url !== $url;

        if ($changed) {
            if ($updateDb && $h->exists) {
                Hotspot::whereKey($h->id)->update(['url' => $url]);
                $h->setAttribute('url', $url);
                $h->syncOriginalAttribute('url');
            } else {
                $h->setAttribute('url', $url); // người gọi tự save()
            }
        }

        return ['ok' => true, 'changed' => $changed, 'url' => $url, 'message' => 'OK'];
    }

    private static function cleanup(string $dir, array $keep): void
    {
        foreach (File::files($dir) as $file) {
            if (!in_array($file->getFilename(), $keep, true)) {
                @unlink($file->getPathname());
            }
        }
    }

   private static function recolor($img, array $color): void
{
    $w = imagesx($img);
    $h = imagesy($img);

    // Lượt 1: tìm màu fill gốc = màu có sắc, xuất hiện nhiều nhất
    $count = [];
    $maxOp = [];
    $sat   = [];
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $c = imagecolorat($img, $x, $y);
            $a = ($c >> 24) & 0x7F;
            if ($a === 127) {
                continue;
            }
            $rgb = $c & 0xFFFFFF;
            if (!isset($sat[$rgb])) {
                $sat[$rgb] = self::rgbToHsl(($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, $rgb & 0xFF)[1];
            }
            if ($sat[$rgb] < self::SAT_THRESHOLD) {
                continue; // trắng/xám: không phải màu fill
            }
            $count[$rgb] = ($count[$rgb] ?? 0) + 1;
            $maxOp[$rgb] = max($maxOp[$rgb] ?? 0, 127 - $a);
        }
    }

    if (empty($count)) {
        return;
    }
    arsort($count);
    $fill   = array_key_first($count);
    $fillOp = max(1, $maxOp[$fill]) / 127; // độ đục của vùng fill gốc (0..1)

    // Vector từ trắng đến màu fill gốc
    $dr  = 255 - (($fill >> 16) & 0xFF);
    $dg  = 255 - (($fill >> 8) & 0xFF);
    $db  = 255 - ($fill & 0xFF);
    $den = $dr * $dr + $dg * $dg + $db * $db;
    if ($den < 900) {
        return; // fill gần như trắng, không có gì để tô
    }

    // Lượt 2: dựng lại từng pixel theo tỷ lệ pha
    $map = [];
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $c = imagecolorat($img, $x, $y);
            $a = ($c >> 24) & 0x7F;
            if ($a === 127) {
                continue;
            }

            if (!array_key_exists($c, $map)) {
                $er = 255 - (($c >> 16) & 0xFF);
                $eg = 255 - (($c >> 8) & 0xFF);
                $eb = 255 - ($c & 0xFF);

                // t = pixel có bao nhiêu phần là màu fill (0 = trắng, 1 = fill thuần)
                $t = max(0, min(1, ($er * $dr + $eg * $dg + $eb * $db) / $den));
                $residual = sqrt(
                    ($er - $t * $dr) ** 2 + ($eg - $t * $dg) ** 2 + ($eb - $t * $db) ** 2
                );

                if ($t < 0.02 || $residual > 60) {
                    $map[$c] = null; // viền trắng hoặc màu lạ: giữ nguyên
                } else {
                    $nr = (int) round($t * $color['r'] + (1 - $t) * 255);
                    $ng = (int) round($t * $color['g'] + (1 - $t) * 255);
                    $nb = (int) round($t * $color['b'] + (1 - $t) * 255);

                    $o    = (127 - $a) / 127; // độ đục hiện tại
                    $newO = self::SOLID_FILL
                        ? $o * (1 - $t) + min(1, $o / $fillOp) * $t // vùng fill lên 100%, mép giữ tỷ lệ
                        : $o;

                    $map[$c] = imagecolorallocatealpha($img, $nr, $ng, $nb, 127 - (int) round($newO * 127));
                }
            }

            if ($map[$c] !== null) {
                imagesetpixel($img, $x, $y, $map[$c]);
            }
        }
    }
}
    private static function load(string $binary)
    {
        $img = @imagecreatefromstring($binary);
        if ($img === false) {
            throw new RuntimeException('File không phải ảnh hợp lệ');
        }
        if (imagesx($img) * imagesy($img) > self::MAX_PIXELS) {
            throw new RuntimeException('Kích thước ảnh quá lớn');
        }
        imagepalettetotruecolor($img);
        imagealphablending($img, false);
        imagesavealpha($img, true);

        return $img;
    }

    private static function encode($img): string
    {
        ob_start();
        imagepng($img);

        return ob_get_clean();
    }

    /** Chỉ cho phép chữ, số, . _ - để mã dự án/mã lô không thể thoát ra khỏi thư mục (../) */
    private static function safe(string $value): string
    {
        $value = trim(preg_replace('/[^A-Za-z0-9._-]/', '_', $value), '.');

        return $value !== '' ? $value : '_';
    }

    private static function parseColor(?string $hex): ?array
{
    if (!$hex || !preg_match('/^#?([0-9a-fA-F]{6})$/', $hex, $m)) {
        return null;
    }
    $hex = strtolower($m[1]);

    return [
        'hex' => $hex,
        'r'   => hexdec(substr($hex, 0, 2)),
        'g'   => hexdec(substr($hex, 2, 2)),
        'b'   => hexdec(substr($hex, 4, 2)),
    ];
}

    private static function fail(Hotspot $h, string $message): array
    {
        return ['ok' => false, 'changed' => false, 'url' => $h->url, 'message' => $message];
    }

    private static function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255; $g /= 255; $b /= 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            return [0, 0, $l];
        }

        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        $h = match ($max) {
            $r => ($g - $b) / $d + ($g < $b ? 6 : 0),
            $g => ($b - $r) / $d + 2,
            default => ($r - $g) / $d + 4,
        };

        return [$h * 60, $s, $l];
    }
}