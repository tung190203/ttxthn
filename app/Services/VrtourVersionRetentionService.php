<?php

namespace App\Services;

use App\Models\VrtourVersion;

// Dịch vụ kiểm tra trạng thái lưu trữ phiên bản panorama của dự án
class VrtourVersionRetentionService
{
    public static function max(): int
    {
        return max((int) config('vrtour.max_versions', 15), (int) config('vrtour.min_keep', 10));
    }

    /** Trạng thái lưu trữ của một tour, dùng cho banner và modal cảnh báo */
    public function status(int $vrtourId): array
    {
        $count = VrtourVersion::where('vrtour_id', $vrtourId)->count();
        $max   = self::max();

        $oldest = $count >= $max
            ? VrtourVersion::where('vrtour_id', $vrtourId)->orderBy('version_number')->first()
            : null;

        return [
            'count'   => $count,
            'max'     => $max,
            'at_cap'  => $count >= $max,
            'oldest'  => $oldest ? [
                'number' => $oldest->version_number,
                'at'     => $oldest->created_at?->format('d/m/Y H:i'),
            ] : null,
        ];
    }
}