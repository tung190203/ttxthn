<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SiteVisitor;
use App\Services\LogExportService;
use App\Exports\SiteVisitorRotateExport;

class RotateSiteVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site-visitors:rotate {--password= : Password for the zip file} {--now : Rotate all logs instead of just 3 months old} {--test : Send email with data but do NOT delete any records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive site visitor logs older than 3 months, zip with password, send to email, and delete from DB';

    /**
     * Execute the console command.
     */
    public function handle(LogExportService $logExportService)
    {
        $isNow = $this->option('now');
        $isTest = $this->option('test');
        $months = $isNow ? null : 3;
        
        $password = $this->option('password') ?: env('LOG_ROTATE_PASSWORD');
        $targetEmailString = env('LOG_ROTATE_EMAIL');

        if (!$password || !$targetEmailString) {
            $this->error("Missing LOG_ROTATE_PASSWORD or LOG_ROTATE_EMAIL in .env");
            return;
        }

        $targetEmails = array_map('trim', explode(',', $targetEmailString));
        $targetEmails = array_filter($targetEmails);

        $this->info("Starting site visitor rotation for " . ($isNow ? "ALL logs" : "logs older than 3 months") . ($isTest ? " [TEST MODE - no data will be deleted]" : ""));

        $result = $logExportService->exportToZip($months, true, $password, 'csv', SiteVisitorRotateExport::class);

        if (!$result) {
            $this->info("No site visitor logs found to rotate.");
            return;
        }

        $zipPath = $result['path'];
        $zipFileName = $result['name'];

        // Send Email
        try {
            Mail::raw("Chào bạn,\n\nĐây là file backup Site Visitor (thống kê lượt truy cập quay lại theo tháng) " . ($isNow ? "(tất cả)" : "(hơn 3 tháng)") . " tính đến tháng " . now()->format('m/Y') . ".\nFile được nén và đặt mật khẩu bảo mật.\n\nTên file: {$zipFileName}\n\nTrân trọng.", function ($message) use ($targetEmails, $zipPath, $zipFileName) {
                $message->to($targetEmails)
                    ->subject("[Site Visitor Backup] " . now()->format('Y-m'))
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
            $cutoffDate = $isNow ? now()->addDay()->toDateString() : now()->subMonths(3)->toDateString();
            $deleted = SiteVisitor::where('visit_date', '<', $cutoffDate)->delete();
            $this->info("Deleted {$deleted} records from database.");
        }

        $this->info("Rotation completed successfully.");
    }
}
