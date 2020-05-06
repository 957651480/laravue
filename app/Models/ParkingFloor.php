<?php

namespace App\Models;

use App\Filter\ParkingFloorScope;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingFloor extends EloquentModel
{
    //
    use SoftDeletes,CityTrait,AuthorTrait,ParkingFloorScope;
    protected $guarded = [];
    protected $table='parking_floor';
    protected $primaryKey='parking_floor_id';

}
