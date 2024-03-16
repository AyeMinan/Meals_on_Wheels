<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition()
    {
        return [
            'type' => 'partner',
            'email' => $this->faker->unique()->safeEmail,
            'user_name' => $this->faker->userName,
            'password' => bcrypt('12345678'),
            'confirm_password' => bcrypt('12345678'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'shop_name' => $this->faker->company,
            'shop_address' => $this->faker->address,
            'image' => $this->faker->imageUrl(200, 200, 'people'),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },

        ];
    }
}
