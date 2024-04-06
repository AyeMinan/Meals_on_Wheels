<?php

namespace Database\Factories;

use App\Models\Caregiver;
use App\Models\Member;
use App\Models\Partner;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $partnerId = Partner::inRandomOrder()->first()->id;
        $memberId = Member::inRandomOrder()->first()->id;
        $caregiverId = Caregiver::inRandomOrder()->first()->id;
        $volunteerId = Volunteer::inRandomOrder()->first()->id;
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
            'image' => $this->faker->imageUrl(),
            'temperature'=>$this->faker->randomFloat(2, -20, 40),
            'partner_id'=>$partnerId,
            'member_id'=>$memberId,
            'caregiver_id'=>$caregiverId,
            'volunteer_id'=>$volunteerId,
        ];
    }
}
