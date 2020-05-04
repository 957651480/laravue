<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class Lottery extends EloquentModel
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='lottery';
    protected $primaryKey='lottery_id';


    public function images()
    {
        return $this->belongsToMany(File::class,'lottery_image','lottery_id','image_id');
    }

    public function city()
    {
        return $this->belongsTo(Region::class,'city_id');
    }
    public function author()
    {
        return $this->belongsTo(\App\Laravue\Models\User::class,'author_id');
    }

    public function prizes()
    {
        return $this->hasMany(LotteryPrize::class,'lottery_id');
    }

}
