<?php

namespace Database\Factories;

use App\Models\CustomBouquet;
use App\Enums\RibbonColor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomBouquetFactory extends Factory
{
    protected $model = CustomBouquet::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'customer_name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'total_price' => $this->faker->numberBetween(100000, 1000000),
            'ribbon_color' => $this->faker->randomElement(RibbonColor::values()),
            'status' => 'draft',
            'special_instructions' => $this->faker->optional()->sentence
        ];
    }
}
