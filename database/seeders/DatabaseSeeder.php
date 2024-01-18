<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use DB;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate old data
        DB::table('cart_items')->delete();
        DB::table('carts')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();
        DB::table('payment_methods')->delete();

        // // Create random data
        // Models\Category::factory(10)->create();
        // Models\Product::factory(10)->create();

        // Create true data

        // Create admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

//        Cart::create([
//            'user_id'
//        ]);

        // Create random users
        User::factory(10)->create();

        foreach (User::all() as $user) {
            Cart::create([
                'user_id' => $user->id,
            ]);
        }

        // Create categories
        Category::insert([
            ['name' => 'Mobile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tablet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptop', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Watch', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create products
        Product::insert([
            ['name' => 'Iphone 14', 'price' => 19900000, 'quantity' => 15, 'category_id' => 1,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samsung galaxy tab a7', 'price' => 2900000, 'quantity' => 31, 'category_id' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptop dell xps 2023', 'price' => 41000000, 'quantity' => 4, 'category_id' => 3,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PC gaming tx23', 'price' => 9090000, 'quantity' => 43, 'category_id' => 4,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samsung galaxy z flip5', 'price' => 13900000, 'quantity' => 7, 'category_id' => 1,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apple watch se2', 'price' => 4089000, 'quantity' => 25, 'category_id' => 5,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo thinkpad x1 nano', 'price' => 18500000, 'quantity' => 22, 'category_id' => 3,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Todor Pelagos', 'price' => 99000000, 'quantity' => 2, 'category_id' => 5,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo xiaoxin pad 2022', 'price' => 3190000, 'quantity' => 10, 'category_id' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PC TTC39 gaming', 'price' => 21720000, 'quantity' => 8, 'category_id' => 4,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create payment methods
        PaymentMethod::insert([
            ['method' => 'cash', 'created_at' => now(), 'updated_at' => now()],
            ['method' => 'master card', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
