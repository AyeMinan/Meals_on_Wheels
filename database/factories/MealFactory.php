<?php

namespace Database\Factories;

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
        return [
            'name' => $this->faker->sentence,
            'ingredients' => $this->faker->paragraph,
            'allergy_information' => $this->faker->paragraph,
            'nutritional_information' => $this->faker->paragraph,
            'dietary_restrictions' => $this->faker->randomElement(['Vegan', 'Vegetarian', 'Gluten-Free', null]),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'is_frozen' => $this->faker->boolean,
            'delivery_status' => $this->faker->boolean,
            'image' => $this->faker->imageUrl(),
        ];
    }
}