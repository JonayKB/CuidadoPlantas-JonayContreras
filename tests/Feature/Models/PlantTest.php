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
        $this->assertEquals($post->plant_id, actual: 1);
        $this->assertEquals($post->user_id, 1);
        $this->assertEquals($post->images->first()->path, 'image.png');
        $this->assertEquals($post->category->name, 'Diseases');

        $this->assertEquals($post->reports, 0);
    }
    public function test_002_get_type(){
        $plant = Plant::find(1);
        $type = $plant->type;
        $this->assertEquals($type->name, 'Cacti');
    }
}
