<?php

namespace App\Models;

use App\Filter\ParkingFloorFilter;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingArea extends EloquentModel
{
    //
    use SoftDeletes,CityTrait,AuthorTrait,ParkingFloorFilter;
    protected $guarded = [];
    protected $table='parking_area';
    protected $primaryKey='parking_area_id';

}
