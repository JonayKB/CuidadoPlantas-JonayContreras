<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer $plant_id
 * @property string $name
 * @property Post[] $posts
 * @property PlantType $type
 */
class Plant extends Model
{
    use SoftDeletes;

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
    protected $fillable = ['name','type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'plant_id', 'plant_id');
    }
    public function type(){
        return $this->belongsTo(PlantType::class,'type','id');
    }
}
