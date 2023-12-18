<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence();
        $slug = Str::slug($title);
        $published = fake()->dateTimeThisYear()->format('Y-m-d H:i:s');

        return [
            'author' => fake()->name(),
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'published' => $published,
        ];
    }
}
