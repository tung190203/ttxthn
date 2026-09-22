<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\VisitLog;
use App\Services\LogExportService;
use App\Exports\VisitLogRotateExport;

class RotateVisitLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visit-logs:rotate
        {--password= : Password for the zip file}
        {--now : Rotate all logs instead of just 1 month old}
        {--month= : Export and delete logs for a specific month (format: YYYY-MM)}
        {--test : Send email with data but do NOT delete any records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive visit logs older than 1 month, zip with password, send to email, and delete from DB';

    /**
     * Execute the console command.
     */
    public function handle(LogExportService $logExportService)
    {
        $isNow = $this->option('now');
        $isTest = $this->option('test');
        $monthOption = $this->option('month');

        $password = $this->option('password') ?: env('LOG_ROTATE_PASSWORD');
        $targetEmailString = env('LOG_ROTATE_EMAIL');

        if (!$password || !$targetEmailString) {
            $this->error("Missing LOG_ROTATE_PASSWORD or LOG_ROTATE_EMAIL in .env");
            return;
        }

        $targetEmails = array_map('trim', explode(',', $targetEmailString));
        $targetEmails = array_filter($targetEmails);

        // --- Xử lý mode --month ---
        if ($monthOption) {
            try {
                $month = Carbon::createFromFormat('Y-m', $monthOption)->startOfMonth();
            } catch (\Exception $e) {
                $this->error("Invalid --month value. Expected format: YYYY-MM (e.g. 2026-08)");
                return;
            }

            $startDate = $month->copy()->startOfMonth()->toDateTimeString();
            $endDate   = $month->copy()->endOfMonth()->toDateTimeString();
            $monthLabel = $month->format('m/Y');

            $this->info("Starting visit log rotation for month: {$monthLabel}" . ($isTest ? " [TEST MODE - no data will be deleted]" : ""));

            $export = new VisitLogRotateExport(null, false, $startDate, $endDate);

            if ($export->query()->count() === 0) {
                $this->info("No visit logs found for {$monthLabel}.");
                return;
            }

            // Dùng LogExportService để tạo ZIP
            $result = $logExportService->exportToZipFromExport($export, $password, "visit_logs_{$month->format('Y_m')}");

            if (!$result) {
                $this->error("Failed to export visit logs for {$monthLabel}.");
                return;
            }

            $zipPath = $result['path'];
            $zipFileName = $result['name'];

            try {
                Mail::raw(
                    "Chào bạn,\n\nĐây là file backup Visit Log tháng {$monthLabel}.\nFile được nén và đặt mật khẩu bảo mật.\n\nTên file: {$zipFileName}\n\nTrân trọng.",
                    function ($message) use ($targetEmails, $zipPath, $zipFileName, $monthOption) {
                        $message->to($targetEmails)
                            ->subject("[Visit Log Backup] {$monthOption}")
                            ->attach($zipPath, [
                                'as' => $zipFileName,
                                'mime' => 'application/zip',
                            ]);
                    }
                );
                $this->info("Email sent successfully to: " . implode(', ', $targetEmails));
            } catch (\Exception $e) {
                $this->error("Failed to send email: " . $e->getMessage());
                return;
            }

            if ($isTest) {
                $this->warn("[TEST MODE] Skipping database cleanup. No records were deleted.");
            } else {
                $deleted = VisitLog::whereBetween('created_at', [$startDate, $endDate])->delete();
                $this->info("Deleted {$deleted} records from database.");
            }

            $this->info("Rotation for {$monthLabel} completed successfully.");
            return;
        }

        // --- Mode mặc định (cũ): hơn 1 tháng hoặc --now ---
        $months = $isNow ? null : 1;

        $this->info("Starting visit log rotation for " . ($isNow ? "ALL logs" : "logs older than 1 month") . ($isTest ? " [TEST MODE - no data will be deleted]" : ""));

        $result = $logExportService->exportToZip($months, true, $password, 'csv', VisitLogRotateExport::class);

        if (!$result) {
            $this->info("No visit logs found to rotate.");
            return;
        }

        $zipPath = $result['path'];
        $zipFileName = $result['name'];

        // Send Email
        try {
            Mail::raw("Chào bạn,\n\nĐây là file backup Visit Log (thống kê tỉ lệ truy cập/bot theo ngày) " . ($isNow ? "(tất cả)" : "(hơn 1 tháng)") . " tính đến tháng " . now()->format('m/Y') . ".\nFile được nén và đặt mật khẩu bảo mật.\n\nTên file: {$zipFileName}\n\nTrân trọng.", function ($message) use ($targetEmails, $zipPath, $zipFileName) {
                $message->to($targetEmails)
                    ->subject("[Visit Log Backup] " . now()->format('Y-m'))
                    ->attach($zipPath, [
                        'as' => $zipFileName,
                        'mime' => 'application/zip',
                    ]);
            });
            $this->info("Email sent successfully to: " . implode(', ', $targetEmails));
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return;
        }

        // Cleanup DB
        if ($isTest) {
            $this->warn("[TEST MODE] Skipping database cleanup. No records were deleted.");
        } else {
            $cutoffDate = $isNow ? now()->addDay() : now()->subMonths(1);
            $deleted = VisitLog::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$deleted} records from database.");
        }

        $this->info("Rotation completed successfully.");
    }
}

