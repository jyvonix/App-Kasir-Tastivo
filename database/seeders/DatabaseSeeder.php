<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil UserSeeder buatan kita (Admin, Kasir, Owner)
        $this->call([
            UserSeeder::class,
        ]);
    }
}