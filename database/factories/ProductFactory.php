<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{

    public function definition()
    {
        return [
          'name_ar' => $this->faker->name(),
          'name_en' => $this->faker->name(),
          'description_ar' => $this->faker->text(),
          'description_en' => $this->faker->text(),
          'photo' => $this->faker->imageUrl(),
          'barcode' => $this->faker->numberBetween(1, 10),
          'price' => $this->faker->randomNumber(),
          'buy_price' => $this->faker->randomNumber(),
          'limit' => $this->faker->randomNumber(),
          'branch_id' => $this->faker->unique(1, 10)->numberBetween(1,10),
          'section_id' => $this->faker->unique(1, 2)->numberBetween(1,2),
        ];
    }


    /*public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }*/
}
