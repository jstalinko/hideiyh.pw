<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial; // Pastikan path ke model Anda sudah benar

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel untuk menghindari duplikasi saat seeder dijalankan ulang
        Testimonial::truncate();

        $testimonials = [
            // Data Awal
            [
                'name' => 'Budi Santoso',
                'jobs' => 'Digital Marketer',
                'content' => 'Gila, *since* pakai HideIyh.pw, akun iklanku jadi lebih aman dari AME (Ad Account Disabled). *No more worries* soal boncos karena traffic bot. *Highly recommended* buat para advertiser!',
                'active' => true,
            ],
            [
                'name' => 'Citra Lestari',
                'jobs' => 'Full-stack Developer',
                'content' => 'API Blocker-nya *powerful* banget. Mudah diintegrasikan ke *project*-ku dan berhasil memfilter 99% *invalid traffic*. *Documentation*-nya juga jelas, *straight to the point*. Keren!',
                'active' => true,
            ],
            [
                'name' => 'Rian Ardiansyah',
                'jobs' => 'Affiliate Marketer',
                'content' => 'Sebagai *affiliate*, data bersih itu segalanya. Fitur Geolocation API ngebantu banget buat nargetin *offer* ke audiens yang tepat. Konversi jadi lebih cihuy! *It\'s a game-changer*.',
                'active' => true,
            ],
            [
                'name' => 'Dewi Anggraini',
                'jobs' => 'Agency Owner',
                'content' => 'Mengelola puluhan domain klien jadi super simpel pakai paket Business. *Unlimited visitor quota is a huge plus*. Gak perlu pusing mikirin *limit* lagi. *Support team*-nya juga gercep!',
                'active' => true,
            ],
             [
                'name' => 'Eko Prasetyo',
                'jobs' => 'Media Buyer',
                'content' => 'Awalnya sempat ragu, tapi setelah coba sendiri ternyata memang ngefek banget buat naikin CTR campaign. Not bad at all.',
                'active' => false, // Contoh testimoni yang tidak aktif
            ],

            // --- DATA TAMBAHAN ---
            [
                'name' => 'Sari Purnomo',
                'jobs' => 'Data Analyst',
                'content' => 'Data A/B testing saya jadi jauh lebih akurat. *Thanks to* ACS Cloaking Script yang bisa misahin mana *real user* mana bot. *Finally*, data yang bisa dipegang!',
                'active' => true,
            ],
            [
                'name' => 'Fajar Nugraha',
                'jobs' => 'E-commerce Manager',
                'content' => 'Campaign Q4 kemarin *saved* banget pakai ini. Bisa nge-block negara yang gak relevan dan fokus ke market potensial. *Overall*, sangat puas dengan hasilnya.',
                'active' => true,
            ],
            [
                'name' => 'Maya Wulandari',
                'jobs' => 'Social Media Specialist',
                'content' => 'Capek kena *banned* terus di Meta Ads. Temen rekomendasiin HideIyh.pw dan *so far so good*. Udah jalan 2 bulan, akun iklan masih ijo royo-royo. *Thank you*!',
                'active' => true,
            ],
            [
                'name' => 'Agus Setiawan',
                'jobs' => 'SEO Specialist',
                'content' => 'Meskipun bukan buat *ads*, saya pakai Blocker API buat ngelindungin web dari *scraper bot*. *It works like a charm*. Beban server jadi lebih ringan.',
                'active' => true,
            ],
            [
                'name' => 'Indah Permata',
                'jobs' => 'UI/UX Designer',
                'content' => 'Dari sisi *user*, *dashboard*-nya *clean* dan intuitif. Gak ribet, gampang banget buat *setup* domain dan *generate smartlink*. *Good job*!',
                'active' => true,
            ],
            [
                'name' => 'Doni Hidayat',
                'jobs' => 'Dropshipper',
                'content' => 'Buat saya yang main *ads* sendirian, ini *tools* wajib. Gak perlu ngerti teknis yang dalem, tinggal pake, dan *it just works*. Hemat waktu, hemat biaya.',
                'active' => true,
            ],
            [
                'name' => 'Kevin Sanjaya',
                'jobs' => 'Programmer',
                'content' => 'Awalnya cuma iseng coba paket Basic, eh ketagihan. Sekarang udah *upgrade* ke Pro. Fitur API-nya bener-bener *unlock new possibilities* buat *growth experiment* kami.',
                'active' => true,
            ],
        ];

        // Looping dan masukkan data ke database
        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}