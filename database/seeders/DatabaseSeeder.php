<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(3)->create();

        \App\Models\User::factory()->create([
            'login' => 'test',
            'name' => 'Test User',
            'password' => Hash::make('abc123'),
            'email' => 'test@example.com',
        ]);
    }
}
