<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package; // Pastikan Anda mengimpor model Package Anda

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel sebelum mengisi untuk menghindari duplikasi data
        // saat seeder dijalankan berulang kali.
        Package::truncate();

        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Untuk pemula dan usaha kecil.',
                'feature_acs_cloaking_script' => true,
                'feature_api_geolocation' => false, // Basic tidak memiliki API Geolocation
                'feature_api_blocker' => false,     // Basic tidak memiliki API Blocker
                'domain_quota' => 5,
                'visitor_quota_perday' => 5000,
                'active' => true,
                'price' => 250000,
                'billing_cycle' => 'month', // Menggunakan 'monthly' lebih baik untuk logika
            ],
            [
                'name' => 'Pro',
                'description' => 'Solusi terbaik untuk profesional.',
                'feature_acs_cloaking_script' => true,
                'feature_api_geolocation' => true,
                'feature_api_blocker' => true,
                'domain_quota' => 10,
                'visitor_quota_perday' => 10000,
                'active' => true,
                'price' => 450000,
                'billing_cycle' => 'month',
            ],
            [
                'name' => 'Business',
                'description' => 'Untuk agensi dan skala besar.',
                'feature_acs_cloaking_script' => true,
                'feature_api_geolocation' => true,
                'feature_api_blocker' => true,
                'domain_quota' => 50,
                // Gunakan -1 untuk menandakan 'unlimited'. Ini adalah praktik umum.
                // Anda bisa menangani logika ini di aplikasi Anda.
                'visitor_quota_perday' => -1, 
                'active' => true,
                'price' => 950000,
                'billing_cycle' => 'month',
            ],
        ];

        // Looping melalui array dan membuat record baru untuk setiap paket
        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}