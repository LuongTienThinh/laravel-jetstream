<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate old data
        Category::truncate();
        Product::truncate();

        // Create new data
        Category::factory(10)->create();
        Product::factory(10)->create();
    }
}
