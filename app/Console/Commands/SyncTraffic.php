<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\Domain;

class SyncTraffic extends Command
{
    protected $signature = 'traffic:sync';
    protected $description = 'Sync Redis traffic counter to database';

    public function handle()
    {
        $keys = Redis::keys('traffic:*');

        foreach ($keys as $key) {

            $signature = explode(':', $key);
            $signature = $signature[1];


            $count = (int) Redis::get('traffic:' . $signature);
            if ($count > 0) {
                $dm = Domain::where('signature', $signature);
                if ($dm->first()) {
                    $dm->increment('traffic_count', $count);

                    $this->info('DOMAIN: ' . $dm->first()->domain . ' Synced ( +' . $count . ' ) Total : ' . $dm->first()->traffic_count);
                    Redis::del('traffic:' . $signature);
                }
            }
        }

        $this->info('Traffic synced!');
    }
}
