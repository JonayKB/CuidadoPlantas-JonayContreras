<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Repository\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;


    public function test_001_get_all_posts()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $posts = $repository->findAll();
        $this->assertEquals(2, $posts->count());
    }

    public function test_002_get_post_by_id()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $post = $repository->findById(1);
        $this->assertEquals('First post', $post->title);
    }

    public function test_003_save_new_post()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $newPost = new Post(['title' => 'Third post', 'description' => 'This is the third post', 'plant_id' => 3, 'user_id' => 3, 'reports' => 0, 'category_id' => 1]);
        $savedPost = $repository->save($newPost);
        $this->assertEquals('Third post', $savedPost->title);
        $this->assertEquals(3,  $repository->findAll()->count());
    }

    public function test_004_update_post()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $post = Post::find(1);
        $post->title = 'Updated First Post';
        $updated = $repository->update($post);
        $this->assertTrue($updated);
        $this->assertEquals('Updated First Post', Post::find(1)->title);
    }

    public function test_005_delete_post()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $deleted = $repository->delete(1);
        $this->assertTrue($deleted);
        $this->assertNull(Post::find(1));
        $this->assertEquals(1, Post::all()->count());
    }

    public function test_006_get_only_trashed_posts()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $repository->delete(1);
        $trashedPosts = $repository->getOnlyTrash();
        $this->assertEquals(1, $trashedPosts->count());
        $this->assertEquals('First post', $trashedPosts->first()->title);
    }

    public function test_007_restore_post()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $repository->delete(1);
        $repository->restore(1);
        $restoredPost = Post::find(1);
        $this->assertNotNull($restoredPost);
        $this->assertEquals('First post', $restoredPost->title);
    }

    public function test_008_get_reported_posts()
    {
        $repository = new PostRepository();
        $repository->setTestMode();

        $post = Post::find(1);
        $post->reports = 3;
        $post->save();

        $reportedPosts = $repository->getReportedPosts();
        $this->assertEquals(1, $reportedPosts->total());
    }

    public function test_009_get_pagination()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $paginatedPosts = $repository->getPagination();
        $this->assertEquals(2, $paginatedPosts->total());
    }

    public function test_010_get_post_with_invalid_id()
    {
        $repository = new PostRepository();
        $repository->setTestMode();
        $post = $repository->findById(999);
        $this->assertNull($post);
    }
}
