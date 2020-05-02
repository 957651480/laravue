<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class House extends EloquentModel
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $primaryKey='house_id';


    public function images()
    {
        return $this->belongsToMany(File::class,'house_image','house_id','image_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
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
