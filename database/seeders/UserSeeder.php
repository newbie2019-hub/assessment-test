<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //Admin user
        User::factory()->create([
            'email' => 'admin@admin.com',
            'isAdmin' => true
        ]);

        //Users
        User::factory(30)->create();
    }
}
