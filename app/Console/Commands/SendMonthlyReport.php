<?php

namespace App\Console\Commands;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'report:send-monthly {--test : Test mode - send report for last 7 days instead of previous month}';

    /**
     * The console command description.
     */
    protected $description = 'Send monthly sales report via email (previous calendar month)';

    public function __construct(private ReportService $reportService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isTest = $this->option('test');
        $email = config('tamtam.report_email');

        if (! $email) {
            $this->error('❌ REPORT_EMAIL not configured in .env');
            Log::error('Monthly report failed: REPORT_EMAIL not set');

            return self::FAILURE;
        }

        // Calculate date range
        if ($isTest) {
            // TEST MODE: Last 7 days
            $to = Carbon::now()->subDay()->format('Y-m-d'); // Yesterday
            $from = Carbon::now()->subDays(7)->format('Y-m-d'); // 7 days ago
            $this->info("🧪 TEST MODE: Sending report for {$from} to {$to}");
        } else {
            // PRODUCTION MODE: Previous calendar month
            $firstDayLastMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastDayLastMonth = Carbon::now()->subMonth()->endOfMonth();

            $from = $firstDayLastMonth->format('Y-m-d');
            $to = $lastDayLastMonth->format('Y-m-d');

            $this->info("📅 Sending monthly report for {$from} to {$to}");
        }

        try {
            $this->info("📧 Sending to: {$email}");

            // Send the report
            $this->reportService->sendEmailReport($from, $to, $email);

            $this->info('✅ Monthly report sent successfully!');
            Log::info("Monthly report sent successfully to {$email} ({$from} to {$to})");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send report: '.$e->getMessage());
            Log::error('Monthly report failed: '.$e->getMessage(), [
                'from' => $from,
                'to' => $to,
                'email' => $email,
                'exception' => $e,
            ]);

            return self::FAILURE;
        }
    }
}
