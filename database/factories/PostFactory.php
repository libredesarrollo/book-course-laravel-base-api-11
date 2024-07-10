<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
 
    public function definition(): array
    {
        // $name = $this->faker->name();
        $name = $this->faker->sentence;
        return [
            'title' => $name,
            'slug' => str($name)->slug(),
            // 'content' => $this->faker->paragraphs(2, true),
            'content' => 'test',
            'description' => $this->faker->paragraphs(1, true),
            'category_id' => $this->faker->randomElement([1, 2, 3]),
            'posted' => $this->faker->randomElement(['yes', 'not']),
            'image' => $this->faker->imageUrl()
        ];
    }
}
