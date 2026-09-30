<?php

namespace App\Supports;

use App\Models\IndustrialProject;
use App\Models\Project;

class SearchQueryParser
{
    // Quy đổi về hectare
    private const TO_HA = ['m2' => 0.0001, 'ha' => 1, 'km2' => 100];

    // Từ khóa => các cách viết đồng nghĩa (SỬA THEO DỮ LIỆU THẬT)
    private const SYNONYMS = [
        'nhà ở' => ['nhà ở', 'đất ở', 'dân cư', 'dịch vụ dân sinh'],
    ];

    // Từ thừa, bị bỏ khỏi từ khóa
    private const STOP_WORDS = ['đất', 'lô', 'tìm', 'kiếm', 'dự án',"thửa","plot","lot","diện tích","acreage"];

    // Tiền tố báo hiệu phần sau là MÃ lô (cụm dài đứng trước)
    private const CODE_PREFIXES = ['lô đất', 'ô đất', 'lô', 'ô'];

    // Từ đứng sau "lô" nhưng KHÔNG phải mã (ví dụ "lô nhà ở", "lô đất công cộng")
    private const NOT_CODE_WORDS = ['nhà','đất', 'ở', 'cây', 'công', 'thương', 'kinh', 'dịch', 'hạ', 'giao',"bãi","biệt","chung","kỹ","kĩ"];
    /** Có dự án nào trùng tên với chuỗi tìm kiếm không? */
    public static function isNameSearch(string $search): bool
    {
        $like = '%' . mb_strtolower(trim($search)) . '%';

        return Project::whereRaw('LOWER(name) LIKE ?', [$like])->exists()
            || IndustrialProject::whereRaw('LOWER(name) LIKE ?', [$like])->exists();
    }

    /** @return array{keywords: string[], codes: string[], areas_ha: float[]} */
    public static function parse(string $search): array
    {
        $text = mb_strtolower(trim($search));
        $text = preg_replace('/km\s*(2|²)/u', 'km2', $text);
        $text = preg_replace('/m\s*(2|²)/u', 'm2', $text);

        // 1. Lấy mã đứng sau "lô", "lô đất", "ô đất"... rồi bỏ khỏi chuỗi
        $codes = [];
        $prefix = implode('|', self::CODE_PREFIXES);
        $codePattern = '/(?<![\p{L}\d])(?:' . $prefix . ')\s+([\p{L}\d][\p{L}\d._\-]*)/u';
        $text = preg_replace_callback($codePattern, function ($m) use (&$codes) {
            if (in_array($m[1], self::NOT_CODE_WORDS, true)) {
                return $m[0]; // không phải mã, giữ nguyên
            }
            $codes[] = $m[1];
            return ' ';
        }, $text);

        // 2. Tách diện tích (số đứng riêng, không dính vào mã như h7.1, khunhao-1)
        $areasHa = [];
        $pattern = '/(?<![\p{L}\d._\-])(\d+(?:[.,]\d+)?)\s*(km2|m2|ha)?(?=\s|$)/u';
        if (preg_match($pattern, $text, $m)) {
            $value = (float) str_replace(',', '.', $m[1]);
            $units = !empty($m[2]) ? [$m[2]] : array_keys(self::TO_HA); // không đơn vị => thử cả 3
            foreach ($units as $u) {
                $areasHa[] = $value * self::TO_HA[$u];
            }
            $text = preg_replace($pattern, ' ', $text, 1);
        }

        // 3. Bỏ stop words, phần còn lại là từ khóa
        $stop = implode('|', self::STOP_WORDS);
        $phrase = preg_replace('/(?<![\p{L}\d])(' . $stop . ')(?![\p{L}\d])/u', ' ', $text);
        $phrase = trim(preg_replace('/\s+/u', ' ', $phrase));

        return [
            'keywords' => self::expandKeywords($phrase),
            'codes'    => $codes,
            'areas_ha' => $areasHa,
        ];
    }

    /** Mở rộng từ khóa theo bảng đồng nghĩa */
    private static function expandKeywords(string $phrase): array
    {
        if ($phrase === '') {
            return [];
        }
        foreach (self::SYNONYMS as $key => $list) {
            if (str_contains($phrase, $key)) {
                return $list;
            }
        }
        return [$phrase];
    }
}