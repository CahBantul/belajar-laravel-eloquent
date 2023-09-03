<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Stmt\Catch_;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category;
        $category->id = "GADGET";
        $category->name = "Gadget";
        $result = $category->save();

        $this->assertTrue($result);
    }

    public function testInsertManyCategories()
    {
        $categories = [];

        for ($i=0; $i < 10; $i++) { 
            $categories[] = [
                "id" => "id $i",
                "name" => "name $i"
            ];
        }

        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(10, $total);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find("FOOD");
        $this->assertNotNull($category);
        $this->assertEquals("FOOD", $category->id);
        $this->assertEquals("Food", $category->name);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find("FOOD");
        $this->assertNotNull($category);
        $category->name = "Food Update";
        $result = $category->update();
        $this->assertTrue($result);
    }

    public function testSelect()
    {
        for ($i=0; $i < 5; $i++) { 
            $category = new Category();
            $category->id = "id $i";
            $category->name = "name $i";
            $category->save();
        };

        $categories = Category::query()->whereNull("description")->get();
        $this->assertEquals(5, $categories->count());
        $categories->each(function ($category){
            $category->description = "update";
            $category->update();
        });
    }

    public function testUpdateMany()
    {
        $categories = [];

        for ($i=0; $i < 10; $i++) { 
            $categories[] = [
                "id" => "id $i",
                "name" => "name $i"
            ];
        }

        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(10, $total);

        Category::query()->whereNull("description")->update([
            "description" => "Updated"
        ]);

        $total = Category::query()->where("description", "=", "Updated")->count();
        $this->assertEquals(10, $total);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $result = $category->delete();
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(0, $total);
    }

    public function testDeleteMany()
    {
        $categories = [];

        for ($i=0; $i < 10; $i++) { 
            $categories[] = [
                "id" => "id $i",
                "name" => "name $i"
            ];
        }

        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(10, $total);

        Category::query()->whereNull("description")->delete();

        $total = Category::query()->count();
        $this->assertEquals(0, $total);
    }

    public function testCreate()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = new Category($request);
        $category->save();

        $this->assertNotNull($category->id);
    }

    public function testCreateMethod()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = Category::query()->create($request);

        $this->assertNotNull($category->id);
    }

    public function testMassUpdate()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            "name" => "Food Updated",
            "description" => "Food Category Updated"
        ];

        $category = Category::find("FOOD");
        $category->fill($request);
        $category->save();

        $this->assertNotNull($category->id);
    }

    public function testRemoveGlobalScope()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = false;
        $category->save();

        $category = Category::find("FOOD");
        $this->assertNull($category);

        $category = Category::query()->withoutGlobalScope(IsActiveScope::class)->find("FOOD");
        $this->assertNotNull($category);
    }

    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $category = Category::find("FOOD");
        $this->assertNotNull($category);

        $products = $category->products;
        $this->assertNotNull($products);
        $this->assertCount(1, $products);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description = "Description Product 1";

        $category->products()->save($product);

        $this->assertNotNull($product->category_id);
    }

    public function testRelationshipQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        $products = $category->products;
        $this->assertNotNull($products);
        $this->assertCount(2, $products);

        $productsOutOfStock = $category->products()->where("stock", ">", 0)->get();
        $this->assertNotNull($productsOutOfStock);
        $this->assertCount(1, $productsOutOfStock);
    }

    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);

        $category = Category::find("FOOD");
        $this->assertNotNull($category);

        $reviews = $category->reviews;
        $this->assertNotNull($reviews);
        $this->assertCount(2, $reviews);


    }
}
