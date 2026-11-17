<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@undangan.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. 5 Tema Awal
        $themes = [
            [
                'name' => 'Adat Jawa Klasik',
                'slug' => 'jawa',
                'category' => 'pernikahan',
                'accent_color' => '#D4AF37',
                'description' => 'Nuansa klasik Jawa dengan perpaduan warna sogan, krem, dan aksen emas bernuansa agung.',
                'is_active' => true,
            ],
            [
                'name' => 'Adat Sunda Parahyangan',
                'slug' => 'sunda',
                'category' => 'pernikahan',
                'accent_color' => '#C59B27',
                'description' => 'Keanggunan budaya Sunda dengan warna hijau zamrud lembut, putih gading, dan aksen emas hangat.',
                'is_active' => true,
            ],
            [
                'name' => 'Adat Palembang Sriwijaya',
                'slug' => 'palembang',
                'category' => 'pernikahan',
                'accent_color' => '#E0A938',
                'description' => 'Kemegahan kerajaan Sriwijaya dengan balutan warna merah marun, hitam elegan, dan keemasan songket.',
                'is_active' => true,
            ],
            [
                'name' => 'Bisnis Luxury & Eksekutif',
                'slug' => 'bisnis-mewah',
                'category' => 'bisnis',
                'accent_color' => '#D4AF37',
                'description' => 'Obsidian gelap eksklusif dengan aksen emas sampanye untuk gala dinner, peluncuran produk, atau pertemuan VIP.',
                'is_active' => true,
            ],
            [
                'name' => 'Bisnis Simple & Modern',
                'slug' => 'bisnis-simple',
                'category' => 'bisnis',
                'accent_color' => '#2563EB',
                'description' => 'Tata letak putih bersih, slate abu-abu, dan aksen biru modern untuk konferensi, seminar, atau temu bisnis.',
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }
    }
}
