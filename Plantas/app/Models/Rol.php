<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $role_id
 * @property string $name
 * @property User[] $users
 */
class Rol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', null, 'id');
    }
}
