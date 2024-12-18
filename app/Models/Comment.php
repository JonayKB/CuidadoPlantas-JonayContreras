<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $comment_id
 * @property integer $parent_comment_id
 * @property integer $post_id
 * @property integer $user_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property Comment[] $replies
 * @property Post $post
 * @property User $user
 */
class Comment extends Model
{
    use SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'comment_id';

    /**
     * @var array
     */
    protected $fillable = ['comment_id','parent_comment_id', 'post_id', 'user_id', 'content', 'created_at', 'updated_at','deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Models\Comment', 'parent_comment_id', 'comment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id', 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', null, 'id');
    }
}
