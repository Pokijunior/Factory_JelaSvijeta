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
        $status = $this->faker->randomElement(['created', 'modified', 'deleted']);

        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'category_id' => $this->faker->numberBetween(1,10),
            'status' => $status
        ];
    }
}
