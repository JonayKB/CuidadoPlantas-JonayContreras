<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_posts(): void
    {
        $category = Category::find(1);
        $posts = $category->posts;
        $this->assertEquals(1, $posts->count());
    }
}
