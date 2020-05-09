<?php

namespace App\Models;

use App\Filter\HouseFilter;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class HouseAppointment extends EloquentModel
{
    use SoftDeletes,CityTrait,HouseFilter;
    //
    protected $guarded = [];
    protected $table='house_appointment';
    protected $primaryKey='house_appointment_id';


    public function house()
    {
        return $this->belongsTo(House::class,'house_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
