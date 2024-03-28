<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Ephraim Lambarte',
            'email' => env('DEFAULT_USER_EMAIL'),
            'password' => Hash::make(env('DEFAULT_USER_PASSWORD')),
        ]);
    }
}
