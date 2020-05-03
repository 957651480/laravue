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


    public function prizes()
    {
        return $this->hasMany(LotteryPrize::class,'lottery_id');
    }

}
