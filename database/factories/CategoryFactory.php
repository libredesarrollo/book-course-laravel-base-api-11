<?php


namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;


class CategoryFactory extends Factory
{
 
    public function definition(): array
    {
        // $name = $this->faker->name();
        $name = $this->faker->sentence;
        return [
            'title' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
