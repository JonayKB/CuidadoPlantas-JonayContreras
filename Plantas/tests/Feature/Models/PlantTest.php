<?php

namespace Tests\Feature;

use App\Models\Plant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlantTest extends TestCase
{
    use RefreshDatabase;
    public function test_001_get_posts(){
        $plant = Plant::find(1);
        $post =$plant->posts()->first();
        $this->assertEquals($post->title, 'First post');
        $this->assertEquals($post->description, 'This is the first post');
        $this->assertEquals($post->plant_id, 1);
        $this->assertEquals($post->user_id, 1);
        $this->assertEquals($post->image, 'image');
        $this->assertEquals($post->imageMimeType, '.jpg');
        $this->assertEquals($post->reports, 0);
    }
}
