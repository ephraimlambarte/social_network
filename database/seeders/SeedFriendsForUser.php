<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Arr;

class SeedFriendsForUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultUserEmail = env('DEFAULT_USER_EMAIL');
        $user = User::where('email', $defaultUserEmail)->first();

        $users = User::factory(5)->create();
        $user->friends()->attach(Arr::pluck($users, 'id'));
        
        $users = User::factory(3)->create();
        foreach ($users as $key => $u) {
            $u->friends()->attach($user->id);
        }
    }
}
