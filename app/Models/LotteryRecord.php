<?php

namespace App\Models;

use App\Filter\LotteryPrizeFilter;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;




class LotteryRecord extends EloquentModel
{
    //
    use CityTrait,AuthorTrait,LotteryPrizeFilter;
    protected $guarded = [];
    protected $primaryKey='lottery_record_id';
    protected $table='lottery_record';



    public function lottery()
    {
        return $this->belongsTo(Lottery::class,'lottery_id');
    }

    public function lottery_prize()
    {
        return $this->belongsTo(LotteryPrize::class,'lottery_prize_id');
    }

}
