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
            'type' => 'member',
            'email' => $this->faker->unique()->safeEmail,
            'user_name' => $this->faker->userName,
            'password' => bcrypt('12345678'),
            'confirm_password' => bcrypt('12345678'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $this->faker->numberBetween(18, 100),
            'phone_number' => $this->faker->phoneNumber,
            'emergency_contact_number' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->date,
            'address' => $this->faker->address,
            'dietary_restriction' => $this->faker->randomElement(['Vegetarian', 'Vegan', 'Gluten-Free']),
            'image' => $this->faker->imageUrl(200, 200, 'people'),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },

        ];
    }
}
