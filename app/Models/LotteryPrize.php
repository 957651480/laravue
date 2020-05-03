<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class LotteryPrize extends EloquentModel
{
    //
    protected $guarded = [];
    protected $primaryKey='lottery_prize_id';
    protected $table='lottery_prize';


    public function lottery()
    {
        return $this->belongsTo(Lottery::class,'lottery_id');
    }


}
