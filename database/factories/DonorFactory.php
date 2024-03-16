<?php

namespace Database\Factories;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donor>
 */
class DonorFactory extends Factory
{
    protected $model = Donor::class;

    public function definition()
    {
        return [
            'type' => 'donor',
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
