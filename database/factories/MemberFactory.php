<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $this->faker->numberBetween(18, 100),
            'emergency_contact_number' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->date,
            'dietary_restriction' => $this->faker->randomElement(['Vegetarian', 'Vegan', 'Gluten-Free']),
            'user_id' => function () {
                $user = \App\Models\User::where('type', 'member')->first();

                // If a user with 'member' type is not found, create one
                if (!$user) {
                    $user = \App\Models\User::factory()->create(['type' => 'member']);
                }

                return $user->id;
            },

        ];
    }
}
