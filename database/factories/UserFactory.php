<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     * PROFESIONAL FIX: Hanya gunakan kolom yang benar-benar ada di database Tastivo.
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'kasir',
            'jabatan' => 'Staff',
            'no_hp' => fake()->phoneNumber(),
            'status' => 1,
            'remember_token' => Str::random(10),
        ];
    }
}