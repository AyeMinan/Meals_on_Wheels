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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([

            MealSeeder::class,
            MemberSeeder::class,
            DonorSeeder::class,
            CaregiverSeeder::class,
            VolunteerSeeder::class,
            ProfileSeeder::class,
            PartnerSeeder::class,
        ]);
    }
}
