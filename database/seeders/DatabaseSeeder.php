<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate old data
        User::truncate();
        Product::truncate();
        DB::table('categories')->delete();

        // // Create random data
        // Models\Category::factory(10)->create();
        // Models\Product::factory(10)->create();

        // Create true data
        User::factory(10)->create();
        Category::insert([
            ['name' => 'Mobile'],
            ['name' => 'Tablet'],
            ['name' => 'Laptop'],
            ['name' => 'PC'],
            ['name' => 'Watch'],
        ]);
        Product::insert([
            ['name' => 'Iphone 14', 'price' => 19900000, 'quantity' => 15, 'category_id' => 1],
            ['name' => 'Samsung galaxy tab a7', 'price' => 2900000, 'quantity' => 31, 'category_id' => 2],
            ['name' => 'Laptop dell xps 2023', 'price' => 41000000, 'quantity' => 4, 'category_id' => 3],
            ['name' => 'PC gaming tx23', 'price' => 9090000, 'quantity' => 43, 'category_id' => 4],
            ['name' => 'Samsung galaxy z flip5', 'price' => 13900000, 'quantity' => 7, 'category_id' => 1],
            ['name' => 'Apple watch se2', 'price' => 4089000, 'quantity' => 25, 'category_id' => 5],
            ['name' => 'Lenovo thinkpad x1 nano', 'price' => 18500000, 'quantity' => 22, 'category_id' => 3],
            ['name' => 'Todor Pelagos', 'price' => 99000000, 'quantity' => 2, 'category_id' => 5],
            ['name' => 'Lenovo xiaoxin pad 2022', 'price' => 3190000, 'quantity' => 10, 'category_id' => 2],
            ['name' => 'PC TTC39 gaming', 'price' => 21720000, 'quantity' => 8, 'category_id' => 4],
        ]);
    }
}
