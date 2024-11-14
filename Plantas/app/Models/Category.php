<?php
namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'categories';
    protected $fillable = ['name'];



    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }
}
