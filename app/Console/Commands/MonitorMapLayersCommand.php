<?php

namespace App\Console\Commands;

use App\Services\MapLayerMonitoringService;
use Illuminate\Console\Command;

class MonitorMapLayersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'map:monitor-layers';

    /**
     * The console command description.
     */
    protected $description = 'Kiểm tra định kỳ trạng thái các nguồn dữ liệu không gian (Module g-03)';

    /**
     * Execute the console command.
     */
    public function handle(MapLayerMonitoringService $monitoringService): int
    {
        $this->info('[' . now()->format('Y-m-d H:i:s') . '] Bắt đầu kiểm tra giám sát các nguồn dữ liệu không gian...');

        $results = $monitoringService->checkAllActiveLayers();

        $this->info("Đã hoàn thành kiểm tra " . count($results) . " layer.");

        foreach ($results as $layerId => $res) {
            $status = $res['status'] ?? 'unknown';
            $latency = $res['latency_ms'] ?? 0;
            $msg = $res['error_message'] ?? 'OK';

            $color = match ($status) {
                'healthy' => 'info',
                'warning' => 'warn',
                'error' => 'error',
                default => 'line'
            };

            $this->{$color}("- Layer #{$layerId}: {$status} ({$latency}ms) - {$msg}");
        }

        return Command::SUCCESS;
    }
}
