<?php
namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'images';
    protected $fillable = ['id','path','post_id','deleted_at'];



    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
