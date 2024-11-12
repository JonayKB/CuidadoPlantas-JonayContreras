<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Repository\RolRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class RolRepositoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_001_get_all_roles(){
        $repository = new RolRepository();
        $repository->setTestMode();
        $roles = $repository->findAll();
        $this->assertEquals(2, $roles->count());
    }
    public function test_002_get_role_by_id(){
        $repository = new RolRepository();
        $repository->setTestMode();
        $role = $repository->findById(1);
        $this->assertEquals('user', $role->name);
    }
    public function test_003_save_new_role(){
        $repository = new RolRepository();
        $repository->setTestMode();
        $newRole = new Rol(['name' => 'test']);
        $savedRole = $repository->save($newRole);
        $this->assertEquals('test', $savedRole->name);
        $this->assertEquals(3, Rol::all()->count());
        $this->assertEquals(3, Rol::find(3)->id);
        $this->assertEquals('test', Rol::find(3)->name);
    }
    public function test_004_update_role(){
        $repository = new RolRepository();
        $repository->setTestMode();
        $role = Rol::find(2);
        $role->name = 'test';
        $updated = $repository->update($role);
        $this->assertEquals('test', Rol::find(2)->name);
        $this->assertEquals(2, Rol::all()->count());

    }
    public function test_005_delete_role(){
        $repository = new RolRepository();
        $repository->setTestMode();
        $role = Rol::find(1);
        $deleted = $repository->delete($role->id);
        $this->assertTrue($deleted);
        $this->assertEquals(1, Rol::all()->count());
        $this->assertEquals(null, Rol::find(1));
        $this->assertEquals(2, Rol::find(2)->id);
        $role->refresh();
        $this->assertTrue($role->trashed());
    }

}
