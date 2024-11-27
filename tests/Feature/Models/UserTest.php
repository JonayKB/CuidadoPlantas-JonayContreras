<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_user_rol()
    {
        $user = User::find(1);
        $rol = Rol::find(2);
        $rolObtained = $user->roles()->first();
        $this->assertEquals($rol->name, $rolObtained->name);
    }
    public function test_get_user_comments()
    {
        $user = User::find(1);
        $comment = Comment::find(1);

        $commentObtained = $user->comments()->first();
        $this->assertEquals($comment->content, $commentObtained->content);
        $this->assertEquals($comment->post_id, $commentObtained->post_id);
        $this->assertEquals($comment->user_id, $commentObtained->user_id);
        $this->assertEquals($comment->parent_comment_id, $commentObtained->parent_comment_id);
        $this->assertEquals($comment->created_at, $commentObtained->created_at);
        $this->assertEquals($comment->updated_at, $commentObtained->updated_at);
    }
    public function test_get_user_posts()
    {
        $user = User::find(1);
        $post = Post::find(1);
        $postObtained = $user->posts()->first();
        $this->assertEquals($post->title, $postObtained->title);
        $this->assertEquals($post->description, $postObtained->description);
        $this->assertEquals($post->plant_id, $postObtained->plant_id);
        $this->assertEquals($post->user_id, $postObtained->user_id);
        $this->assertEquals($post->image, $postObtained->image);
        $this->assertEquals($post->imageMimeType, $postObtained->imageMimeType);
        $this->assertEquals($post->created_at, $postObtained->created_at);
        $this->assertEquals($post->updated_at, $postObtained->updated_at);
        $this->assertEquals($post->reports, $postObtained->reports);
    }
}
