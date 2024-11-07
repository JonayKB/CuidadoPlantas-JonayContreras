<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $comment_id
 * @property integer $parent_comment_id
 * @property integer $post_id
 * @property integer $user_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property Comment $comment
 * @property Post $post
 * @property User $user
 */
class Comment extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'comment_id';

    /**
     * @var array
     */
    protected $fillable = ['parent_comment_id', 'post_id', 'user_id', 'content', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'parent_comment_id', 'comment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post', null, 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', null, 'user_id');
    }
}
