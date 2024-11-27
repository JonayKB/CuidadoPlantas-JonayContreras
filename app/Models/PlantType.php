<?php
namespace App\Models;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantType extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'plants_types';
    protected $fillable = ['name','deleted_at'];

    public function plants()
    {
        return $this->hasMany(Plant::class, 'type_id', 'id');
    }
}
