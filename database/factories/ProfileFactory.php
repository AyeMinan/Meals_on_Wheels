<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
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
        $defaultImage = "1712213156.jpg";
        return [
            'image' => $defaultImage,
            'user_name' => $this->faker->name,
            'address' => $this->faker->address,
            'township' => $this->faker->city,
            'phone_number' => $this->faker->numerify('+951234567'),

        ];
    }


}
