<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_001_get_all_Users(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $users = $repository->findAll();
        $this->assertEquals(2, $users->count());
    }
    public function test_002_get_User_by_id(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = $repository->findById(1);
        $this->assertEquals('Jonay', $user->name);
    }
    public function test_003_save_new_User(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $newUser = new User(['name' => 'test2', 'email' => 'test2@gmail.com','password'=>Hash::make('password')]);
        $savedUser = $repository->save($newUser);
        $this->assertEquals('test2', $savedUser->name);
        $this->assertEquals(3, User::all()->count());
        $this->assertEquals(3, User::find(3)->id);
        $this->assertEquals('test2', User::find(3)->name);
    }
    public function test_004_update_Usere(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(2);
        $user->name = 'test';
        $updated = $repository->update($user);
        $this->assertEquals('test', User::find(2)->name);
        $this->assertEquals(2, User::all()->count());

    }
    public function test_005_delete_Usere(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(1);
        $deleted = $repository->delete($user->id);
        $this->assertTrue($deleted);
        $this->assertEquals(1, User::all()->count());
        $this->assertEquals(null, User::find(1));
        $this->assertEquals(2, User::find(2)->id);
        $user->refresh();
        $this->assertTrue($user->trashed());
    }

}
