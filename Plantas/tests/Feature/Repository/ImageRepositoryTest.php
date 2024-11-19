<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ImageRepositoryTest extends TestCase
{
    use RefreshDatabase;
    protected ImageRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ImageRepository();
        $this->repository->setTestMode();
    }

    public function test_findById_returns_image_when_found()
    {
        $image = $this->repository->findById(1);
        $this->assertNotNull($image);
        $this->assertEquals('image.png', $image->path);
    }

    public function test_findById_returns_null_when_not_found()
    {
        $image = $this->repository->findById(999);
        $this->assertNull($image);
    }

    public function test_findAll_returns_all_images()
    {
        $images = $this->repository->findAll();
        $this->assertCount(3, $images);
    }

    public function test_save_creates_image()
    {
        $newImage = new Image([
            'path' => 'new_image.png',
            'post_id' => 2,
        ]);
        $savedImage = $this->repository->save($newImage);
        $this->assertNotNull($savedImage);
        $this->assertDatabaseHas('images', ['path' => 'new_image.png']);
    }

    public function test_update_updates_image()
    {
        $image = Image::find(1);
        $image->path = 'updated_image.png';
        $updated = $this->repository->update($image);
        $this->assertTrue($updated);
        $this->assertDatabaseHas('images', ['path' => 'updated_image.png']);
    }

    public function test_delete_deletes_image()
    {
        $image = Image::find(1);
        $deleted = $this->repository->delete($image->id);
        $this->assertTrue($deleted);
        $this->assertSoftDeleted('images', ['id' => 1]);
    }

    public function test_delete_returns_true_when_image_not_found()
    {
        $deleted = $this->repository->delete(999);
        $this->assertTrue($deleted);
    }

}
