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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $this->faker->date,
            'user_id' => function () {
              return  \App\Models\User::factory()->create(['type' => 'volunteer'])->id;
            }
        ];
    }
}
