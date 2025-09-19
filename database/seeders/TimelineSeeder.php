<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timelines')->insert([
            [
                'title' => 'New Dashboard Design',
                'description' => 'Redesigned the admin dashboard for better UX.',
                'type' => 'feature',
            ],
            [
                'title' => 'Bug Fix in Login Module',
                'description' => 'Resolved issue with incorrect password handling.',
                'type' => 'fix',
            ],
            [
                'title' => 'Performance Improvement',
                'description' => 'Optimized database queries for faster loading.',
                'type' => 'improvement',
            ],
            [
                'title' => 'General Maintenance',
                'description' => 'Updated dependencies and cleaned up old code.',
                'type' => 'general',
            ],
        ]);
    }
}
