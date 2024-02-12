<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'first_name' => 'patrick',
            'last_name' => 'scully',
            'email' => 'patrick@possibleweb.com',
            'phone_number' => '1234567890',
            'password' => bcrypt('asdf6900'),
        ]);

        $locations = ['elizabeth', 'dilworth', 'harrisburg', 'ballantyne', 'noda'];

        foreach ($locations as $location) {
            \App\Models\Location::factory()->create([
                'name' => $location,
            ]);
        }
    }
}
