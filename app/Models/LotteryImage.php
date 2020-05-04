<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LotteryImage extends Pivot
{
    //
    protected $guarded = [];
    protected $table='lottery_image';
    protected $primaryKey='lottery_image_id';


    public function file()
    {
        return $this->belongsToMany(File::class,'image_id');
    }

    public function lottery()
    {
        return $this->belongsToMany(Lottery::class,'lottery_id');
    }
}
