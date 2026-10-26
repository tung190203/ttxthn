<?php

namespace App\Services;

use App\Models\VrtourVersion;
use App\Models\Panorama;
use App\Models\VrtourVersionItem;
use App\Models\ConnectMap;
use App\Models\Investor;
use App\Models\Plan;
use App\Models\Project;
use App\Models\WelcomeScreen;
use Illuminate\Support\Str;

// sự khác giữa 2 phiên bản VrtourVersion, trả về dạng mảng để hiển thị trong bảng
class VrtourVersionDiffService
{
    public const STATUS_ADDED     = 'added';
    public const STATUS_REMOVED   = 'removed';
    public const STATUS_MODIFIED  = 'modified';
    public const STATUS_UNCHANGED = 'unchanged';
    public const HTML_FIELDS = [
    'content', 'content_en', 'description',
    'content1', 'content2', 'content3', 'content1_en', 'content2_en', 'content3_en',
    ];
    public const IGNORED_FIELDS = ['vrtour_id'];

    public const PANORAMA_LABELS = [
        'title'          => 'Tiêu đề',
        'title_en'       => 'Tiêu đề (EN)',
        'content'        => 'Nội dung',
        'content_en'     => 'Nội dung (EN)',
        'audio'          => 'Audio',
        'audio_en'       => 'Audio (EN)',
        'label_audio'    => 'Nhãn audio',
        'ids'            => 'IDs',
        'parent_id'      => 'Panorama cha',
        'status'         => 'Trạng thái',
        'user_id'        => 'Người tạo',
        'approval_level' => 'Cấp duyệt',
        'max_approval'   => 'Cấp duyệt tối đa',
        'is_draft'       => 'Bản nháp',
    ];

    public const HOTSPOT_LABELS = [
        'potision'                       => 'Mã lô',
        'investment_product_statuses_id' => 'Trạng thái đầu tư',
        'tooltip'                        => 'Tooltip (VI)',
        'tooltip_en'                     => 'Tooltip (EN)',
        'acreage'                        => 'Diện tích',
        'unit'                           => 'Đơn vị',
        'intended_use'                   => 'Mục đích sử dụng',
        'product_type'                   => 'Loại sản phẩm',
        'price'                          => 'Giá gốc',
        'lease_price'                    => 'Giá thuê',
        'sale_price'                     => 'Giá bán',
        'transaction_note'               => 'Ghi chú giao dịch',
        'available_amenities'            => 'Tiện ích sẵn có',
        'contact_info'                   => 'Thông tin liên hệ',
        'url'                            => 'Ảnh',
        'opacity'                        => 'Hiển thị',
        'status'                         => 'Trạng thái duyệt',
        'user_id'                        => 'Người cập nhật',
        'ip_link'                        => 'Link lô đất',
    ];

    public const SKIN_TYPES = ['welcome', 'connect_map', 'plan', 'investor', 'location'];

    public const SKIN_NAMES = [
        'welcome'     => 'Màn hình chào mừng',
        'connect_map' => 'Sơ đồ liên kết vùng',
        'plan'        => 'Kế hoạch triển khai',
        'investor'    => 'Thông tin chủ đầu tư',
        'location'    => 'Vị trí',
    ];

    public const SKIN_LABELS = [
        'welcome' => [
            'title' => 'Tên dự án', 'description' => 'Mô tả dự án', 'voice' => 'Voice',
            'show_investor' => 'Hiển thị chủ đầu tư', 'investor_img' => 'Logo chủ đầu tư',
            'investor_desc1' => 'Thông tin chủ đầu tư 1', 'investor_desc2' => 'Thông tin chủ đầu tư 2',
            'investor_desc3' => 'Thông tin chủ đầu tư 3',
        ],
        'connect_map' => [
            'image' => 'Ảnh VN', 'image_en' => 'Ảnh EN',
            'content' => 'Nội dung chi tiết VN', 'content_en' => 'Nội dung chi tiết EN',
        ],
        'plan' => [
            'show' => 'Hiển thị',
            'image1' => 'Ảnh giai đoạn 1', 'title1' => 'Giai đoạn 1', 'title1_en' => 'Giai đoạn 1 EN',
            'content1' => 'Nội dung giai đoạn 1 VN', 'content1_en' => 'Nội dung giai đoạn 1 EN',
            'image2' => 'Ảnh giai đoạn 2', 'title2' => 'Giai đoạn 2', 'title2_en' => 'Giai đoạn 2 EN',
            'content2' => 'Nội dung giai đoạn 2 VN', 'content2_en' => 'Nội dung giai đoạn 2 EN',
            'image3' => 'Ảnh giai đoạn 3', 'title3' => 'Giai đoạn 3', 'title3_en' => 'Giai đoạn 3 EN',
            'content3' => 'Nội dung giai đoạn 3 VN', 'content3_en' => 'Nội dung giai đoạn 3 EN',
        ],
        'investor' => [
            'name' => 'Tên', 'name_en' => 'Tên EN', 'image' => 'Ảnh',
            'content1' => 'Nội dung 1 VN', 'content1_en' => 'Nội dung 1 EN',
            'content2' => 'Nội dung 2 VN', 'content2_en' => 'Nội dung 2 EN',
            'content3' => 'Nội dung 3 VN', 'content3_en' => 'Nội dung 3 EN',
            'website' => 'Website', 'sologan' => 'Tên khác', 'sologan_en' => 'Tên khác EN', 'status' => 'Hiển thị',
        ],
        'location' => ['location_in_tour' => 'Vị trí'],
    ];

    public static function labelsFor(string $scope): array
    {
        return match ($scope) {
            VrtourVersion::SCOPE_HOTSPOT => self::HOTSPOT_LABELS,
            VrtourVersion::SCOPE_SKIN    => [],   // Skin có nhãn riêng theo từng loại, xem buildCells
            default                      => self::PANORAMA_LABELS,
        };
    }

    public function diff(VrtourVersion $old, VrtourVersion $new, string $entityType = 'panorama'): array
    {
        return $this->diffStates($this->stateAt($old, $entityType),$this->stateAt($new, $entityType));
    }

    /** So sánh hai trạng thái dạng [key => data] */
    public function diffStates(array $from, array $to): array
    {
        $keys = collect(array_merge(array_keys($from), array_keys($to)))->unique()->sort()->values();

        $rows = [];
        $summary = [
            self::STATUS_ADDED => 0,
            self::STATUS_REMOVED => 0,
            self::STATUS_MODIFIED => 0,
            self::STATUS_UNCHANGED => 0,
        ];

        foreach ($keys as $k) {
            $o = $from[$k] ?? null;
            $n = $to[$k] ?? null;
            $changed = [];

            if ($o === null && $n === null) {
                continue;
            } elseif ($o === null) {
                $status = self::STATUS_ADDED;
            } elseif ($n === null) {
                $status = self::STATUS_REMOVED;
            } else {
                $changed = $this->changedFields($o, $n);
                $status  = $changed ? self::STATUS_MODIFIED : self::STATUS_UNCHANGED;
            }

            $summary[$status]++;
            $rows[] = [
                'key'            => (string) $k,
                'status'         => $status,
                'old'            => $o,
                'new'            => $n,
                'changed_fields' => $changed,
            ];
        }

        return ['rows' => $rows, 'summary' => $summary];
    }

    /**
     * Trạng thái đầy đủ của một scope tại thời điểm $version: [entity_key => data].
     * Lấy trạng thái hiện tại, rồi lùi về bằng before_data của item sớm nhất sau $version.
     */
    public function stateAt(VrtourVersion $version, string $entityType): array
    {
        $multi = $version->scope === VrtourVersion::SCOPE_SKIN;
        $types = $multi ? self::SKIN_TYPES : [$entityType];

        $state = $this->liveState($version->scope, $version->vrtour_id, $entityType);

        $later = VrtourVersionItem::query()
            ->join('vrtour_versions as v', 'v.id', '=', 'vrtour_version_items.version_id')
            ->where('v.vrtour_id', $version->vrtour_id)
            ->where('v.scope', $version->scope)
            ->where('v.version_number', '>', $version->version_number)
            ->whereIn('vrtour_version_items.entity_type', $types)
            ->orderBy('v.version_number')
            ->orderBy('vrtour_version_items.id')
            ->select('vrtour_version_items.*')
            ->get()
            ->unique(fn($it) => $it->entity_type . '|' . $it->entity_key);

        foreach ($later as $it) {
            $k = $multi ? $it->entity_type . '|' . $it->entity_key : $it->entity_key;
            if ($it->before_data === null) {
                unset($state[$k]);
            } else {
                $state[$k] = $it->before_data;
            }
        }

        return $state;
    }

    /** Dữ liệu hiện tại trong DB, đã chuẩn hóa. Hotspot và skin sẽ thêm ở bước sau. */
    public function liveState(string $scope, int $vrtourId, string $entityType): array
    {
        return match ($scope) {
            VrtourVersion::SCOPE_PANORAMA => Panorama::where('vrtour_id', $vrtourId)
                ->where('is_draft', 0)->get()
                ->mapWithKeys(fn($p) => [
                    (string) $p->getKey() => VrtourVersionItem::normalize($p->only(VrtourVersionItem::PANORAMA_FIELDS)),
                ])->all(),
            VrtourVersion::SCOPE_HOTSPOT => array_map(fn($d) => VrtourVersionItem::normalize($d),VrtourVersionItem::hotspotStates($vrtourId)),
            VrtourVersion::SCOPE_SKIN => $this->skinLive($vrtourId),
            default => [],
        };
    }

    private function skinLive(int $vrtourId): array
    {
        $models = ['welcome' => WelcomeScreen::class,'connect_map' => ConnectMap::class,'plan' => Plan::class,'investor' => Investor::class,];

        $out = [];
        foreach ($models as $type => $class) {
            if ($m = $class::where('vrtour_id', $vrtourId)->first()) {
                $out[$type . '|' . $m->getKey()] = VrtourVersionItem::normalize(VrtourVersionItem::skinData($type, $m));
            }
        }
        if ($p = Project::query()->find($vrtourId)) {
            $out['location|' . $vrtourId] = VrtourVersionItem::normalize(VrtourVersionItem::skinData('location', $p));
        }

        return $out;
    }

    /** Mô tả một item cho báo cáo PDF */
    public function describeItem(VrtourVersionItem $it, array $labels, array $lookups = [], $users = [], bool $withValues = false): array
    {
        $isSkin = isset(self::SKIN_LABELS[$it->entity_type]);
        if ($isSkin) {
            $labels = self::SKIN_LABELS[$it->entity_type];
        }

        $data  = $it->after_data ?? $it->before_data ?? [];
        $title = $isSkin
            ? self::SKIN_NAMES[$it->entity_type]
            : ($data['title'] ?? (isset($data['potision']) ? Str::after($data['potision'], 'cmss_') : '#' . $it->entity_key));

        $noValue = ['url', 'ip_link', 'image', 'image_en', 'investor_img', 'image1', 'image2', 'image3'];

        $fields = [];
        if ($it->action === VrtourVersionItem::ACTION_MODIFIED) {
            $before = $it->before_data ?? [];
            $after  = $it->after_data ?? [];

            foreach ($this->changedFields($before, $after) as $f) {
                $label = $labels[$f] ?? $f;

                // Đường dẫn ảnh/link dài, chỉ ghi nhận có đổi
                if (!$withValues || in_array($f, $noValue, true)) {
                    $fields[] = $label;
                    continue;
                }

                $from = Str::limit($this->toText($f, $before[$f] ?? null, $users, $lookups), 60);
                $to   = Str::limit($this->toText($f, $after[$f] ?? null, $users, $lookups), 60);
                $fields[] = "{$label}: {$from} → {$to}";
            }
        }

        return ['action' => $it->action, 'key' => $it->entity_key, 'title' => $title, 'fields' => $fields];
    }

    private function changedFields(array $old, array $new): array
    {
        $fields = array_diff(array_unique(array_merge(array_keys($old), array_keys($new))), self::IGNORED_FIELDS);

        return array_values(array_filter(
            $fields,
            fn($f) => $this->norm($old[$f] ?? null) !== $this->norm($new[$f] ?? null)
        ));
    }

    private function norm($v): string
    {
        if (is_array($v)) {
            return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return is_bool($v) ? (string) (int) $v : (string) ($v ?? '');
    }
    /**
     * So sánh 2 chuỗi theo từng từ.
     * Trả về [html_cũ, html_mới], đã escape, phần khác nhau được bọc <del>/<ins>.
     */
    public function highlight(string $old, string $new): array
    {
        $split = fn(string $s) => preg_split('/(\s+)/u', $s, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [];
        $a = $split($old);
        $b = $split($new);
        $n = count($a);
        $m = count($b);

        // Bỏ phần đầu và đuôi giống nhau để bảng LCS nhỏ lại
        $pre = 0;
        while ($pre < $n && $pre < $m && $a[$pre] === $b[$pre]) {
            $pre++;
        }
        $suf = 0;
        while ($suf < $n - $pre && $suf < $m - $pre && $a[$n - 1 - $suf] === $b[$m - 1 - $suf]) {
            $suf++;
        }

        $midA = array_slice($a, $pre, $n - $pre - $suf);
        $midB = array_slice($b, $pre, $m - $pre - $suf);
        $x = count($midA);
        $y = count($midB);

        $ops = []; // [loại, token]: eq | del | ins
        foreach (array_slice($a, 0, $pre) as $t) {
            $ops[] = ['eq', $t];
        }

        if ($x * $y > 3000000) {
            // Quá dài, coi như thay thế cả đoạn để khỏi tốn RAM
            foreach ($midA as $t) $ops[] = ['del', $t];
            foreach ($midB as $t) $ops[] = ['ins', $t];
        } else {
            // dp[i][j] = độ dài LCS của midA[i..] và midB[j..]
            $dp = array_fill(0, $x + 1, array_fill(0, $y + 1, 0));
            for ($i = $x - 1; $i >= 0; $i--) {
                for ($j = $y - 1; $j >= 0; $j--) {
                    $dp[$i][$j] = $midA[$i] === $midB[$j]
                        ? $dp[$i + 1][$j + 1] + 1
                        : max($dp[$i + 1][$j], $dp[$i][$j + 1]);
                }
            }

            $i = $j = 0;
            while ($i < $x && $j < $y) {
                if ($midA[$i] === $midB[$j]) {
                    $ops[] = ['eq', $midA[$i]];
                    $i++;
                    $j++;
                } elseif ($dp[$i + 1][$j] >= $dp[$i][$j + 1]) {
                    $ops[] = ['del', $midA[$i]];
                    $i++;
                } else {
                    $ops[] = ['ins', $midB[$j]];
                    $j++;
                }
            }
            for (; $i < $x; $i++) $ops[] = ['del', $midA[$i]];
            for (; $j < $y; $j++) $ops[] = ['ins', $midB[$j]];
        }

        foreach (array_slice($a, $n - $suf) as $t) {
            $ops[] = ['eq', $t];
        }

        // Khoảng trắng nằm giữa hai đoạn thay đổi thì coi là một phần của thay đổi,
        // để các từ liền nhau gộp thành một khối thay vì tô rời từng từ.
        $count = count($ops);
        $merged = [];
        foreach ($ops as $idx => [$type, $tok]) {
            $isGap = $type === 'eq'
                && trim($tok) === ''
                && $idx > 0 && $idx < $count - 1
                && $ops[$idx - 1][0] !== 'eq'
                && $ops[$idx + 1][0] !== 'eq';

            if ($isGap) {
                $merged[] = ['del', $tok];
                $merged[] = ['ins', $tok];
            } else {
                $merged[] = [$type, $tok];
            }
        }

        // Gom các token liên tiếp cùng loại trên từng bên
        $segments = ['old' => [], 'new' => []];
        $push = function (string $side, string $type, string $tok) use (&$segments) {
            $last = count($segments[$side]) - 1;
            if ($last >= 0 && $segments[$side][$last][0] === $type) {
                $segments[$side][$last][1] .= $tok;
            } else {
                $segments[$side][] = [$type, $tok];
            }
        };

        foreach ($merged as [$type, $tok]) {
            if ($type === 'eq') {
                $push('old', 'eq', $tok);
                $push('new', 'eq', $tok);
            } elseif ($type === 'del') {
                $push('old', 'del', $tok);
            } else {
                $push('new', 'ins', $tok);
            }
        }

        $render = function (array $segs): string {
            $html = '';
            foreach ($segs as [$type, $text]) {
                if ($type === 'eq') {
                    $html .= e($text);
                } elseif ($type === 'del') {
                    $html .= '<del class="diff-del">' . e($text) . '</del>';
                } else {
                    $html .= '<ins class="diff-ins">' . e($text) . '</ins>';
                }
            }
            return $html;
        };

        return [$render($segments['old']), $render($segments['new'])];
    }

    /** Thêm cho mỗi dòng: 'show' (field cần hiện) và 'cells' (HTML đã escape + highlight) */
    public function buildCells(array $rows, $users, string $scope = 'panorama', array $lookups = []): array
    {
        return array_map(function ($row) use ($scope, $users, $lookups) {
            $labels = self::labelsFor($scope);

            if ($scope === VrtourVersion::SCOPE_SKIN) {
                $entity = explode('|', $row['key'])[0];
                $labels = self::SKIN_LABELS[$entity] ?? [];
                $row['section'] = self::SKIN_NAMES[$entity] ?? $entity;
            }

            $show  = $row['status'] === self::STATUS_MODIFIED ? $row['changed_fields'] : array_keys($labels);
            $cells = ['old' => [], 'new' => []];

            foreach ($show as $f) {
                $o = $row['old'] ? $this->toText($f, $row['old'][$f] ?? null, $users, $lookups) : null;
                $n = $row['new'] ? $this->toText($f, $row['new'][$f] ?? null, $users, $lookups) : null;

                if ($row['status'] === self::STATUS_MODIFIED && $o !== null && $n !== null) {
                    [$oh, $nh] = $this->highlight($o, $n);
                } else {
                    $oh = $o !== null ? e($o) : null;
                    $nh = $n !== null ? e($n) : null;
                }

                $cells['old'][$f] = $oh;
                $cells['new'][$f] = $nh;
            }

            $row['show']   = $show;
            $row['cells']  = $cells;
            $row['labels'] = $labels;

            return $row;
        }, $rows);
    }

    private function toText(string $field, $val, $users, array $lookups = []): string
    {
        if (is_null($val) || $val === '') {
            return '—';
        }
        if (is_bool($val) || in_array($field, ['is_draft', 'show', 'show_investor'], true)) {
            return $val ? 'Có' : 'Không';
        }
        if ($field === 'user_id') {
            return $users[$val] ?? ('User #' . $val);
        }
        if ($field === 'investment_product_statuses_id') {
            return $lookups['status'][$val] ?? ('#' . $val);
        }
        if ($field === 'product_type') {
            return $lookups['product_type'][$val] ?? ('#' . $val);
        }
        if ($field === 'unit') {
            return (string) $val === '1' ? 'km' : 'ha';
        }

        $text = (string) $val;

        if (in_array($field, self::HTML_FIELDS, true)) {
            $text = preg_replace('#</(p|div|li|h[1-6])>#i', "\n", $text);
            $text = preg_replace('#<br[^>]*>#i', "\n", $text);
            $text = strip_tags($text);
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = str_replace("\xC2\xA0", ' ', $text);
            $text = trim($text);
        }

        return $text;
    }
}
