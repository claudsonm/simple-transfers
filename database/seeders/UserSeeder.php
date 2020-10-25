<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::withoutEvents(function () {
            User::factory()->withWallet()->create([
                'name' => 'Alice',
                'email' => 'alice@example.com',
            ]);
            User::factory()->withWallet()->create([
                'name' => 'Bob',
                'email' => 'bob@example.com',
            ]);
            User::factory(10)->withWallet()->create();
        });
    }
}
