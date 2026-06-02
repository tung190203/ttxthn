<?php

namespace App\Console\Commands;

use App\Models\RailwayLine;
use Illuminate\Console\Command;

class SyncRailwayLines extends Command
{
    protected $signature = 'railways:sync {--file=public/js/railways.js}';

    protected $description = 'Sync railway line names from public/js/railways.js into the railway_lines table';

    public function handle(): int
    {
        $path = base_path($this->option('file'));

        if (!is_file($path)) {
            $this->error("Không tìm thấy file: {$path}");
            return self::FAILURE;
        }

        $content = file_get_contents($path);
        preg_match_all('/^\s*"([^"]+)"\s*:\s*\{\s*"color"\s*:\s*"([^"]*)"/m', $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->error('Không đọc được danh sách tuyến từ file railways.js.');
            return self::FAILURE;
        }

        foreach ($matches as $index => $match) {
            RailwayLine::updateOrCreate(
                ['name' => $match[1]],
                [
                    'color' => $match[2] ?: null,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $this->info('Đã đồng bộ ' . count($matches) . ' tuyến ĐSĐT vào DB.');

        return self::SUCCESS;
    }
}
