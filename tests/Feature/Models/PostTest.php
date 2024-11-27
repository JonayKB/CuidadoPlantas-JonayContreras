<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    public function test_001_get_User(){
        $post = Post::find(1);
        $user = $post->user;
        $this->assertEquals($user->name, 'Jonay');
        $this->assertEquals($user->email, 'jonaykb@gmail.com');
        $this->assertEquals($user->verified, false);
    }
    public function test_002_get_Plant(){
        $post = Post::find(1);
        $plant = $post->plant;
        $this->assertEquals($plant->name, 'Cactus');
    }
    public function test_003_get_Comments(){
        $post = Post::find(1);
        $comment = $post->comments()->first();
        $this->assertEquals($comment->content, 'First comment');
    }

}
