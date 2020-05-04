<?php

namespace App\Models;

use App\Scopes\CityScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends EloquentModel
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $table='information';
    protected $primaryKey='information_id';


    /**
     * 模型的「booted」方法
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CityScope());
    }


    public function images()
    {
        return $this->belongsToMany(File::class,'information_image','information_id','image_id');
    }

    public function city()
    {
        return $this->belongsTo(Region::class,'city_id');
    }
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class,'author_id');
    }

}
