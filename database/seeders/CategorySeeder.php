<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Kategori Pengeluaran
            ['name' => 'Makanan & Minuman', 'type' => 'expense', 'icon' => '🍔', 'color' => 'amber'],
            ['name' => 'Transportasi & Bensin', 'type' => 'expense', 'icon' => '🚗', 'color' => 'blue'],
            ['name' => 'Belanja Kebutuhan Harian', 'type' => 'expense', 'icon' => '🛒', 'color' => 'indigo'],
            ['name' => 'Tagihan & Utilitas (Listrik/Air/WiFi)', 'type' => 'expense', 'icon' => '💡', 'color' => 'yellow'],
            ['name' => 'Hiburan & Liburan', 'type' => 'expense', 'icon' => '🎬', 'color' => 'purple'],
            ['name' => 'Kesehatan & Medis', 'type' => 'expense', 'icon' => '💊', 'color' => 'rose'],
            ['name' => 'Pendidikan & Buku', 'type' => 'expense', 'icon' => '📚', 'color' => 'cyan'],
            ['name' => 'Sedekah & Donasi', 'type' => 'expense', 'icon' => '🤲', 'color' => 'emerald'],

            // Kategori Pemasukan
            ['name' => 'Gaji Pokok', 'type' => 'income', 'icon' => '💼', 'color' => 'emerald'],
            ['name' => 'Bonus & THR', 'type' => 'income', 'icon' => '🎁', 'color' => 'green'],
            ['name' => 'Freelance / Side Project', 'type' => 'income', 'icon' => '💻', 'color' => 'teal'],
            ['name' => 'Investasi & Dividen', 'type' => 'income', 'icon' => '📈', 'color' => 'blue'],
            ['name' => 'Cashback & Hadiah', 'type' => 'income', 'icon' => '🏷️', 'color' => 'amber'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name'], 'type' => $cat['type']],
                $cat
            );
        }
    }
}

