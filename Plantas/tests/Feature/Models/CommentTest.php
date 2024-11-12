<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    public function test_001_get_Comment(){

        $comment = Comment::find(2);
        $parentComment = $comment->comment;
        $this->assertEquals($parentComment->content, 'First comment');
    }
    public function test_002_get_post(){
        $comment = Comment::find(2);
        $post = $comment->post;
        $this->assertEquals($post->title, 'First post');
    }
    public function test_003_get_user(){
        $comment = Comment::find(2);
        $user = $comment->user;
        $this->assertEquals($user->name, 'test');
    }
}
