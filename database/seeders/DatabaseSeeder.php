<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');        #DESACTIVA LAS LLAVES FORANEAS
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuarios = 100;
        $cantidadCategories = 10;
        $cantidadProducts = 200;
        $cantidadTransactions = 500;

        User::factory()->count($cantidadUsuarios)->create();
        Category::factory()->count($cantidadCategories)->create();
        Product::factory()->count($cantidadProducts)->create()->each(
            function ($producto){
                $categories = Category::all()->random(mt_rand(1,5))->pluck('id'); #el metodo pluck filtra solo por el id
                $producto->categories()->attach($categories);
            }
        );
        Transaction::factory()->count($cantidadTransactions)->create();

    }
}
