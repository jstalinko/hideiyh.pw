<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessVisitorLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitorlogs:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queued visitor logs from Redis into database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchSize = 500;
        $logs = [];

        // Pop items up to the batch limit
        for ($i = 0; $i < $batchSize; $i++) {
            $logItem = \Illuminate\Support\Facades\Redis::lpop('visitor_logs_queue');
            if (!$logItem) {
                break; // Queue is empty
            }
            $logs[] = json_decode($logItem, true);
        }

        if (!empty($logs)) {
            \App\Models\VisitorLog::insert($logs);
            $this->info("Successfully processed " . count($logs) . " visitor logs.");
        } else {
            $this->info("No visitor logs to process.");
        }
    }
}
