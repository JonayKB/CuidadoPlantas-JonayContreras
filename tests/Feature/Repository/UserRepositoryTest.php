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
    public function test_001_get_all_Users()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $users = $repository->findAll();
        $this->assertEquals(3, $users->count());
    }
    public function test_002_get_User_by_id()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = $repository->findById(1);
        $this->assertEquals('Jonay', $user->name);
    }
    public function test_003_save_new_User()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $newUser = new User(['name' => 'test2', 'email' => 'test2@gmail.com', 'password' => Hash::make('password')]);
        $savedUser = $repository->save($newUser);
        $this->assertEquals('test2', $savedUser->name);
        $this->assertEquals(4, User::all()->count());
        $this->assertEquals(4, User::find(4)->id);
        $this->assertEquals('test2', User::find(4)->name);
    }
    public function test_004_update_Usere()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(2);
        $user->name = 'test';
        $updated = $repository->update($user);
        $this->assertEquals('test', User::find(2)->name);
        $this->assertEquals(3, User::all()->count());
    }
    public function test_005_delete_Usere()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(1);
        $deleted = $repository->delete($user->id);
        $this->assertTrue($deleted);
        $this->assertEquals(2, User::all()->count());
        $this->assertEquals(null, User::find(1));
        $this->assertEquals(2, User::find(2)->id);
        $user->refresh();
        $this->assertTrue($user->trashed());
    }
    public function test_006_get_User_by_email()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = $repository->findByEmail('test@gmail.com');
        $this->assertEquals('test', $user->name);
    }
    public function test_007_get_User_by_email_with_trashed()
    {
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = $repository->findById(2);
        $repository->delete($user->id);
        $user->refresh();
        $this->assertTrue($user->trashed());
        $this->assertNull($repository->findById(2));
        $userTrash = $repository->findByIdWithTrash(2);
        $this->assertEquals('test', $userTrash->name);
        $this->assertEquals(2, $userTrash->id);
    }
    public function test_008_get_only_trash(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(2);
        $repository->delete($user->id);
        $user->refresh();
        $this->assertTrue($user->trashed());
        $users = $repository->getOnlyTrash();
        $this->assertEquals(1, $users->count());
        $this->assertEquals(2, $users->first()->id);
        $this->assertEquals('test', $users->first()->name);
    }
    public function test_009_restore_user(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = User::find(2);
        $repository->delete($user->id);
        $user->refresh();
        $this->assertTrue($user->trashed());
        $repository->restore(2);
        $user->refresh();
        $this->assertFalse($user->trashed());
    }
    public function test_010_get_not_verifieds(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $users = $repository->getNotVerified();
        $this->assertEquals(2, $users->total());
    }
    public function test_011_get_pagination(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $users = $repository->getPagination();
        $this->assertEquals(3, $users->total());
    }
    public function test_012_verify_user(){
        $repository = new UserRepository();
        $repository->setTestMode();
        $user = $repository->findById(2);
        $repository->verify($user->id);
        $user->refresh();
        $this->assertEquals(1,$user->verified);
    }



}
