<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Mail\WeeklyBehaviorReportMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Guest;
use App\Models\Interest;
use App\Models\User;
use Carbon\Carbon;

class SendWeeklyBehaviorReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly investor behavior report to managers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = Carbon::now()->startOfWeek();

        $newGuestsCount = Guest::where('created_at', '>=', $dateFrom)->count();
        $totalBookmarks = Interest::where('created_at', '>=', $dateFrom)->count();
        $totalVisits = \App\Models\VisitLog::where('created_at', '>=', $dateFrom)->count();
        if ($totalVisits == 0) $totalVisits = 1;
        $conversionRate = round(($newGuestsCount / $totalVisits) * 100, 2);

        $reportData = [
            'newGuestsCount' => $newGuestsCount,
            'totalBookmarks' => $totalBookmarks,
            'conversionRate' => $conversionRate,
        ];

        // Send to users with role 'LĐ' or 'QL' (Assuming user model has roles, we'll just log or send to an admin email for now)
        // In real app, loop through managers. Here we just send to a dummy address or admin.
        $adminEmail = config('mail.from.address', 'admin@example.com');
        
        Mail::to($adminEmail)->send(new WeeklyBehaviorReportMail($reportData));

        $this->info('Weekly behavior report sent successfully to ' . $adminEmail);
    }
}
