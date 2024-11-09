<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $user_id
 * @property integer $role_id
 * @property string $name
 * @property string $password
 * @property boolean $verified
 * @property string $created_at
 * @property string $updated_at
 * @property Role $role
 * @property Comment[] $comments
 * @property Post[] $posts
 */
class User extends Authenticatable
{
    public $fillable = ['name', 'email', 'password','verified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'user_rol', 'user_id','id', '',''); //TODO
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment', null, 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', null, 'user_id');
    }
}
