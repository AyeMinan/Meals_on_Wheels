<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'shop_name' => $this->faker->company,
            'shop_address' => $this->faker->address,
            'user_id' => function () {
                return  \App\Models\User::factory()->create(['type' => 'partner'])->id;
              }
        ];
    }
}
