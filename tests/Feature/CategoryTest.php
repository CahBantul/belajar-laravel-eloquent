<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
