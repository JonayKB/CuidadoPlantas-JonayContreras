<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Repository\RolRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolRepositoryTest extends TestCase
{
    use RefreshDatabase;


    public function test_001_get_all_roles()
    {
        $repository = new RolRepository();
        $repository->setTestMode();
        $roles = $repository->findAll();
        $this->assertEquals(2, $roles->count());
    }

    public function test_002_get_role_by_id()
    {
        $repository = new RolRepository();
        $repository->setTestMode();
        $role = $repository->findById(1);
        $this->assertEquals('user', $role->name);
    }

    public function test_003_save_new_role()
    {
        $repository = new RolRepository();
        $repository->setTestMode();
        $newRole = new Rol(['name' => 'editor']);
        $savedRole = $repository->save($newRole);
        $this->assertEquals('editor', $savedRole->name);
        $this->assertEquals(3, Rol::all()->count());
    }

    public function test_004_update_role()
    {
        $repository = new RolRepository();
        $repository->setTestMode();
        $role = Rol::find(1);
        $role->name = 'updated_user';
        $updated = $repository->update($role);
        $this->assertTrue($updated);
        $this->assertEquals('updated_user', Rol::find(1)->name);
    }

    public function test_005_delete_role()
    {
        $repository = new RolRepository();
        $repository->setTestMode();
        $deleted = $repository->delete(1);
        $this->assertTrue($deleted);
        $this->assertNull(Rol::find(1));
        $this->assertEquals(1, Rol::all()->count());
    }
}
