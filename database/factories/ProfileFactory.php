<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model=Profile::class;
    public function definition(): array
    {
        return [
            'image' => $this->faker->imageUrl(),
            'user_name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->numerify('+951234567'),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
        ];
    }
}
