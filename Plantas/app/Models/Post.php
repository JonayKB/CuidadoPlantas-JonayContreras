<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $post_id
 * @property integer $plant_id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $imageMimeType
 * @property string $created_at
 * @property string $updated_at
 * @property integer $reports
 * @property Comment[] $comments
 * @property Plant $plant
 * @property User $user
 */
class Post extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * @var array
     */
    protected $fillable = ['plant_id', 'user_id', 'title', 'description', 'image', 'imageMimeType', 'created_at', 'updated_at', 'reports'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment', null, 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant()
    {
        return $this->belongsTo('App\Models\Plant', null, 'plant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', null, 'user_id');
    }
}
