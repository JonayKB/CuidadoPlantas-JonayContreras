<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RolTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_users()
    {
        $rol = Rol::find(1);
        $user = User::find(2);

        $obtainedUser = $rol->users()->first();
        $this->assertEquals($user->name, $obtainedUser->name);
        $this->assertEquals($user->email, $obtainedUser->email);
        $this->assertEquals($user->password, $obtainedUser->password);
        $this->assertEquals($user->verified, $obtainedUser->verified);
        $this->assertEquals($user->created_at, $obtainedUser->created_at);
        $this->assertEquals($user->updated_at, $obtainedUser->updated_at);
        



    }

}
