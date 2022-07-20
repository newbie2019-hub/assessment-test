<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Father', 'Mother', 'Sister', 'Brother'];

        foreach($data as $relationship)
        {
            Relationship::create(['type' => $relationship]);
        }

        $this->call([
            UserSeeder::class
        ]);
    }
}
