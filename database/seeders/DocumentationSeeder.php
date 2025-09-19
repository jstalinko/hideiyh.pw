<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentationSeeder extends Seeder
{
    public function run()
    {
        DB::table('documentations')->insert([
            [
                'title' => 'Getting Started',
                'slug' => Str::slug('Getting Started'),
                'content' => 'This is the introduction to the documentation.',
                'excerpt' => 'Introduction to the documentation.',
                'order' => 1,
                'category' => 'general',
                'version' => '1.0.0',
                'is_published' => true,
                'published_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Installation Guide',
                'slug' => Str::slug('Installation Guide'),
                'content' => 'This section explains how to install the application.',
                'excerpt' => 'How to install the application.',
                'order' => 2,
                'category' => 'installation',
                'version' => '1.0.0',
                'is_published' => true,
                'published_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'API Reference',
                'slug' => Str::slug('API Reference'),
                'content' => 'Detailed API documentation for developers.',
                'excerpt' => 'API documentation.',
                'order' => 3,
                'category' => 'api',
                'version' => '1.0.0',
                'is_published' => false,
                'published_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
