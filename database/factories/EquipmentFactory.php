<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => $this->faker->name(),
            'unit' => $this->faker->randomElement(['pc', 'm/l']),
            'profit_percentage' => $this->faker->randomFloat(),
            'weight' => $this->faker->randomFloat(),
            'in_stock' => $this->faker->randomNumber(),
            'effective_qty' => $this->faker->randomNumber(),
            'min_qty' => $this->faker->randomNumber(),
            'purchase_value' => $this->faker->randomFloat(),
            'unit_value' => $this->faker->randomFloat(),
            'replace_value' => $this->faker->randomFloat(),
        ];
    }
}
