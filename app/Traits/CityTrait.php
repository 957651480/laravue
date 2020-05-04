<?php


namespace App\Traits;


use App\Models\Region;

trait CityTrait
{

    public function city()
    {
        return $this->belongsTo(Region::class,'city_id');
    }
}
