<?php

namespace Database\Factories;

use App\Models\Caregiver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caregiver>
 */
class CaregiverFactory extends Factory
{
    protected $model = Caregiver::class;

    public function definition()
    {
        return [

            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $this->faker->date,
            'relationship_with_member' => $this->faker->randomElement(['Family', 'Friend', 'Relative']),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },

        ];
    }
}
