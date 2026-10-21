<?php

namespace App\Services;

use App\Models\VrtourVersion;

// sự khác giữa 2 phiên bản VrtourVersion, trả về dạng mảng để hiển thị trong bảng
class VrtourVersionDiffService
{
    public const STATUS_ADDED     = 'added';
    public const STATUS_REMOVED   = 'removed';
    public const STATUS_MODIFIED  = 'modified';
    public const STATUS_UNCHANGED = 'unchanged';
    public const HTML_FIELDS = ['content', 'content_en'];


    /** Field không tính là thay đổi nội dung (chỉnh theo nghiệp vụ) */
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

    public function diff(VrtourVersion $old, VrtourVersion $new, string $entityType = 'panorama'): array
    {
        $load = fn(VrtourVersion $v) => $v->items()->where('entity_type', $entityType)->get(['entity_key', 'content_hash', 'data'])->keyBy('entity_key');
        $oldItems = $load($old);
        $newItems = $load($new);
        $keys = $oldItems->keys()->merge($newItems->keys())->unique()->sortBy(fn($k) => (int) $k)->values();
        $rows = [];
        $summary = [
            self::STATUS_ADDED => 0,
            self::STATUS_REMOVED => 0,
            self::STATUS_MODIFIED => 0,
            self::STATUS_UNCHANGED => 0,
        ];

        foreach ($keys as $key) {
            $o = $oldItems->get($key);
            $n = $newItems->get($key);
            $changed = [];

            if (!$o) {
                $status = self::STATUS_ADDED;
            } elseif (!$n) {
                $status = self::STATUS_REMOVED;
            } else {
                // Hash giống nhau thì chắc chắn không đổi. Hash khác thì so lại từng field
                // để tránh báo "sửa" oan khi hash bị lệch do đổi cách tính.
                if ($o->content_hash !== $n->content_hash) {
                    $changed = $this->changedFields($o->data ?? [], $n->data ?? []);
                }
                $status = $changed ? self::STATUS_MODIFIED : self::STATUS_UNCHANGED;
            }

            $summary[$status]++;
            $rows[] = [
                'key'    => $key,
                'status' => $status,
                'old'    => $o?->data,
                'new'    => $n?->data,
                'changed_fields' => $changed,
            ];
        }

        return ['rows' => $rows, 'summary' => $summary];
    }

    private function changedFields(array $old, array $new): array
    {
        $fields = array_diff(array_unique(array_merge(array_keys($old), array_keys($new))), self::IGNORED_FIELDS);

        return array_values(array_filter(
            $fields,
            fn($f) => ($old[$f] ?? null) !== ($new[$f] ?? null)
        ));
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
    public function buildCells(array $rows, $users): array
    {
        $allFields = array_keys(self::PANORAMA_LABELS);

        return array_map(function ($row) use ($allFields, $users) {
            $show  = $row['status'] === self::STATUS_MODIFIED ? $row['changed_fields'] : $allFields;
            $cells = ['old' => [], 'new' => []];

            foreach ($show as $f) {
                $o = $row['old'] ? $this->toText($f, $row['old'][$f] ?? null, $users) : null;
                $n = $row['new'] ? $this->toText($f, $row['new'][$f] ?? null, $users) : null;

                if ($row['status'] === self::STATUS_MODIFIED && $o !== null && $n !== null) {
                    [$oh, $nh] = $this->highlight($o, $n);
                } else {
                    $oh = $o !== null ? e($o) : null;
                    $nh = $n !== null ? e($n) : null;
                }

                $cells['old'][$f] = $oh;
                $cells['new'][$f] = $nh;
            }

            $row['show']  = $show;
            $row['cells'] = $cells;

            return $row;
        }, $rows);
    }

    private function toText(string $field, $val, $users): string
    {
        if (is_null($val) || $val === '') {
            return '—';
        }
        if (is_bool($val) || $field === 'is_draft') {
            return $val ? 'Có' : 'Không';
        }
        if ($field === 'user_id') {
            return $users[$val] ?? ('User #' . $val);
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
