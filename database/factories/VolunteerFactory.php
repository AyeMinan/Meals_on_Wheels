<?php

namespace Database\Factories;

use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteer>
 */
class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition()
    {
        return [
            'type' => 'volunteer',
            'email' => $this->faker->unique()->safeEmail,
            'user_name' => $this->faker->userName,
            'password' => bcrypt('12345678'),
            'confirm_password' => bcrypt('12345678'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone_number' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->date,
            'address' => $this->faker->address,
            'image' => $this->faker->imageUrl(200, 200, 'people'),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },

        ];
    }
}
