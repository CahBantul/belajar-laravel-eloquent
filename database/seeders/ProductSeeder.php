<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->id = 1;
        $product->name = "Product 1";
        $product->description = "Description 1";
        $product->category_id = "FOOD";
        $product->price = 4000;
        $product->save();

        $product = new Product();
        $product->id = 2;
        $product->name = "Product 2";
        $product->description = "Description 2";
        $product->category_id = "FOOD";
        $product->price = 2000;
        $product->stock = 10;
        $product->save();

        $product = new Product();
        $product->id = 3;
        $product->name = "Product 3";
        $product->description = "Description 3";
        $product->category_id = "FOOD";
        $product->price = 5000;
        $product->stock = 10;
        $product->save();
    }
}
