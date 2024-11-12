<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $plant_id
 * @property string $name
 * @property Post[] $posts
 */
class Plant extends Model
{
    public $timestamps = false;
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'plant_id';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'plant_id', 'plant_id');
    }
}
