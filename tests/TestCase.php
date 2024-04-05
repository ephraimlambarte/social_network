<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DefaultUserSeeder;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    protected $user;

    public function setUp() : void {
        parent::setUp();
        $this->seed(DefaultUserSeeder::class);
        $this->setUser();
    }
    protected function setUser() {
        $user = User::where('email', env('DEFAULT_USER_EMAIL'))->first();
        $this->user = $user;
    }
}
