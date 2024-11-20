<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_post(): void
    {
        $image = Image::find(1);
        $post = $image->post;
        $this->assertEquals('First post', $post->title);
    }
}
