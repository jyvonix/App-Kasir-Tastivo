<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan ini ada agar tidak error saat panggil DB

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Kategori sesuai request
        $kategoris = [
            [
                'id' => 1, 
                'nama_kategori' => 'Burger', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 2, 
                'nama_kategori' => 'Donuts', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 3, 
                'nama_kategori' => 'Ice Cream', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 4, 
                'nama_kategori' => 'Hot Dog', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 5, 
                'nama_kategori' => 'Potato', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 6, 
                'nama_kategori' => 'Fuchka', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 7, 
                'nama_kategori' => 'Pizza', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 8, 
                'nama_kategori' => 'Chicken', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 9, 
                'nama_kategori' => 'Dessert', 
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 10, 
                'nama_kategori' => 'Minuman', 
                'created_at' => now(), 'updated_at' => now()
            ],
        ];

        // Masukkan data ke database
        DB::table('kategori')->insert($kategoris);
    }
}