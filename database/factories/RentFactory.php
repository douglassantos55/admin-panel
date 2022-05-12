<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rent>
 */
class RentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'qty_days' => $this->faker->randomNumber(),
            'customer_id' => $this->faker->randomNumber(),
            'period_id' => $this->faker->randomNumber(),
            'payment_type_id' => $this->faker->randomNumber(),
            'payment_method_id' => $this->faker->randomNumber(),
            'payment_condition_id' => $this->faker->randomNumber(),
            'transporter_id' => $this->faker->randomNumber(),
        ];
    }
}
