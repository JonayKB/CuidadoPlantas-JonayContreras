<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
        $this->repository->setTestMode();
    }


    public function test_it_can_find_category_by_id()
    {
        $category = Category::first();
        $foundCategory = $this->repository->findById($category->id);

        $this->assertNotNull($foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
    }


    public function test_it_returns_null_when_category_not_found()
    {
        $foundCategory = $this->repository->findById(999);

        $this->assertNull($foundCategory);
    }


    public function test_it_can_save_category()
    {
        $category = new Category(['name' => 'Test Category']);
        $savedCategory = $this->repository->save($category);

        $this->assertNotNull($savedCategory);
        $this->assertEquals('Test Category', $savedCategory->name);
    }


    public function test_it_can_update_category()
    {
        $category = Category::first();
        $category->name = 'Updated Category';

        $updated = $this->repository->update($category);

        $this->assertTrue($updated);
        $this->assertEquals('Updated Category', $category->name);
    }


    public function test_it_can_delete_category()
    {
        $category = Category::first();
        $deleted = $this->repository->delete($category->id);

        $this->assertTrue($deleted);
        $this->assertNull(Category::find($category->id));
    }


    public function test_it_can_restore_deleted_category()
    {
        $category = Category::first();
        $category->delete();

        $restored = $this->repository->restore($category->id);

        $this->assertTrue($restored);
        $this->assertNotNull(Category::find($category->id));
    }


    public function test_it_can_return_only_deleted_categories()
    {
        $category = Category::first();
        $category->delete();

        $deletedCategories = $this->repository->getOnlyTrash();

        $this->assertCount(1, $deletedCategories);
    }


    public function test_it_can_paginate_categories()
    {
        $paginatedCategories = $this->repository->getPagination();

        $this->assertNotEmpty($paginatedCategories);
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $paginatedCategories);
    }

    public function test_it_can_return_all_categories()
    {
        $categories = $this->repository->findAll();


        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Collection::class, $categories);
        $this->assertGreaterThan(0, $categories->count());
    }
}
