<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Repository\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CommentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CommentRepository();
        $this->repository->setTestMode();
    }

    public function test_findById_returns_comment_when_found()
    {
        $comment = $this->repository->findById(1);
        $this->assertNotNull($comment);
        $this->assertEquals('First comment', $comment->content);
    }

    public function test_findById_returns_null_when_not_found()
    {
        $comment = $this->repository->findById(999);
        $this->assertNull($comment);
    }

    public function test_findAll_returns_all_comments()
    {
        $comments = $this->repository->findAll();
        $this->assertCount(3, $comments);
    }

    public function test_save_creates_comment()
    {
        $newComment = new Comment([
            'content' => 'New comment',
            'post_id' => 1,
            'user_id' => 3,
        ]);
        $savedComment = $this->repository->save($newComment);
        $this->assertNotNull($savedComment);
        $this->assertDatabaseHas('comments', ['content' => 'New comment']);
    }

    public function test_update_updates_comment()
    {
        $comment = Comment::find(1);
        $comment->content = 'Updated comment';
        $updated = $this->repository->update($comment);
        $this->assertTrue($updated);
        $this->assertDatabaseHas('comments', ['content' => 'Updated comment']);
    }

    public function test_delete_deletes_comment()
    {
        $comment = Comment::find(1);
        $deleted = $this->repository->delete($comment->comment_id);
        $this->assertTrue($deleted);
    }

    public function test_delete_returns_true_when_comment_not_found()
    {
        $deleted = $this->repository->delete(999);
        $this->assertTrue($deleted);
    }

}
