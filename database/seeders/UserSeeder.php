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
        // 1. Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator Utama',
                'email' => 'admin@tastivo.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'jabatan' => 'Manager',
                'no_hp' => '08123456789',
                'status' => 1
            ]
        );

        // 2. Kasir
        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'nama' => 'Staff Kasir',
                'email' => 'kasir@tastivo.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'jabatan' => 'Staff',
                'no_hp' => '08123456789',
                'status' => 1
            ]
        );

        // 3. Owner
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'nama' => 'Pemilik Toko',
                'email' => 'owner@tastivo.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'jabatan' => 'Owner',
                'no_hp' => '08123456789',
                'status' => 1
            ]
        );
    }
}