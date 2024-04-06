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
        // \App\Models\User::factory(50)->create();

        \App\Models\User::factory()->create([
            'user_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'confirm_password' => '12345678',
            'type' => 'admin',
            'role_as'=> 1
        ]);

        $this->call([


            MemberSeeder::class,
            DonorSeeder::class,
            CaregiverSeeder::class,
            VolunteerSeeder::class,
            PartnerSeeder::class,
            ProfileSeeder::class,
            MealSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
