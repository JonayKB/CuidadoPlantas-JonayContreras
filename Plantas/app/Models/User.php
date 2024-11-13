<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property integer $user_id
 * @property integer $role_id
 * @property string $name
 * @property string $password
 * @property boolean $verified
 * @property string $created_at
 * @property string $updated_at
 * @property string $email_verified_at
 * @property string $remember_token
 * @property Rol $role
 * @property Comment[] $comments
 * @property Post[] $posts
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    public $fillable = ['name', 'email', 'password','verified', 'created_at', 'updated_at','email_verified_at','remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class,
         'user_rol',
         'user_id',
         'rol_id');
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
        return $this->hasMany('App\Models\Post', null, 'id');
    }

}
