<?php

namespace App\Models;

use App\Scopes\CityScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends EloquentModel
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $table='house';
    protected $primaryKey='house_id';


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
        return $this->belongsToMany(File::class,'house_image','house_id','image_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }

    public function city()
    {
        return $this->belongsTo(Region::class,'city_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class,'author_id');
    }

    public function setHouseRegionAttribute($value)
    {
        $this->attributes['house_region'] = json_encode($value);
    }

    public function getHouseRegionAttribute($value)
    {
        return json_decode($value,true);
    }

}
