<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;

class ProductTest extends TestCase
{
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find(1);
        $this->assertNotNull($product);

        $category = $product->category;
        $this->assertNotNull($category);
        $this->assertEquals("FOOD", $category->id);
    }

    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        $this->assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        $this->assertNotNull($cheapestProduct);

        $this->assertEquals("2", $cheapestProduct->id);
        info($cheapestProduct);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        $this->assertNotNull($mostExpensiveProduct);

        $this->assertEquals("3", $mostExpensiveProduct->id);
        info($mostExpensiveProduct);
    }
}
