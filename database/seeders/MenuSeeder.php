<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'name' => 'Nasi Goreng',
                'price' => 15000,
                'group' => 'Makanan',
                'barcode' => '123456',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80',
                'description' => 'Nasi goreng spesial dengan telur dan ayam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ayam Bakar',
                'price' => 20000,
                'group' => 'Makanan',
                'barcode' => '789012',
                'image' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80',
                'description' => 'Ayam bakar madu dengan sambal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sop Ayam',
                'price' => 12000,
                'group' => 'Sup',
                'barcode' => '111222',
                'image' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=400&q=80',
                'description' => 'Sup ayam hangat dengan sayuran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Es Teh Manis',
                'price' => 5000,
                'group' => 'Minuman',
                'barcode' => '345678',
                'image' => 'https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=400&q=80',
                'description' => 'Es teh manis dingin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jus Jeruk',
                'price' => 8000,
                'group' => 'Minuman',
                'barcode' => '555666',
                'image' => 'https://images.unsplash.com/photo-1504674900247-ec6b0b1b798e?auto=format&fit=crop&w=400&q=80',
                'description' => 'Jus jeruk segar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}