<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Scopes\IsActiveScope;
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
}
