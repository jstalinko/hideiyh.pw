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

            $signature = explode('traffic:', $key);
            $signature = $signature[1];
            $myKey=preg_replace('/laravel_database_/' , '' , $key);
            $count = (int) Redis::get($myKey);
            if ($count > 0) {
                 $dm=Domain::where('signature', $signature);
                 $dm->increment('traffic_count', $count);

                $this->info('DOMAIN: ' . $dm->first()->domain. ' Synced ( +'.$count. ' ) Total : '.$dm->first()->traffic_count);
                Redis::del($myKey);
            }
        }
     
        $this->info('Traffic synced!');
    }
}
