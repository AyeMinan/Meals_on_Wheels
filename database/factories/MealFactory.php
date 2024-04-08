<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $partnerId = User::where('type', 'partner')->inRandomOrder()->first()->id;
        $defaultImage = "1712213230.jpg";
        return [
            'name' => $this->faker->sentence,
            'ingredients' => $this->faker->paragraph,
            'allergy_information' => $this->faker->paragraph,
            'nutritional_information' => $this->faker->paragraph,
            'dietary_restrictions' => $this->faker->randomElement(['Vegan', 'Vegetarian', 'Gluten-Free', null]),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'is_frozen' => $this->faker->boolean,
            'delivery_status' => $this->faker->boolean,
            'is_preparing' =>  $this->faker->boolean,
            'is_finished' =>  $this->faker->boolean,
            'is_pickup' =>  $this->faker->boolean,
            'is_delivered' =>  $this->faker->boolean,
            'image' => $defaultImage,
            'temperature'=>$this->faker->randomFloat(2, -20, 40),
            'partner_id'=>$partnerId,
        ];
    }
}
