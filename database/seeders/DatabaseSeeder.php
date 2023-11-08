<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $tag = Tag::factory(count: 2);
        $ingredient = Ingredient::factory(count: 10);
        
        Category::factory(count: 10)
            ->has(Meal::factory(count: 2)
            ->has($tag)
            ->has($ingredient))
            ->create();
    }
}
