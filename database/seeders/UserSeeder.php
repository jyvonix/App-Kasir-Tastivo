<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Admin
        User::create([
            'nama' => 'Administrator Utama',
            'username' => 'admin',
            'email' => 'admin@tastivo.com',
            'password' => 'password', // Model User akan otomatis hash ini
            'role' => 'admin',
            'jabatan' => 'Manager',
            'no_hp' => '08123456789',
            'status' => 1
        ]);

        // 2. Buat Kasir
        User::create([
            'nama' => 'Staff Kasir',
            'username' => 'kasir',
            'email' => 'kasir@tastivo.com',
            'password' => 'password',
            'role' => 'kasir',
            'jabatan' => 'Staff',
            'no_hp' => '08123456789',
            'status' => 1
        ]);

        // 3. Buat Owner
        User::create([
            'nama' => 'Pemilik Toko',
            'username' => 'owner',
            'email' => 'owner@tastivo.com',
            'password' => 'password',
            'role' => 'owner',
            'jabatan' => 'Owner',
            'no_hp' => '08123456789',
            'status' => 1
        ]);
    }
}