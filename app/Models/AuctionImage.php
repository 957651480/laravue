<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuctionImage extends Pivot
{
    //
    protected $guarded = [];
    protected $table='auction_image';
    protected $primaryKey='auction_image_id';


    public function file()
    {
        return $this->belongsToMany(File::class,'image_id');
    }

    public function auction()
    {
        return $this->belongsToMany(Lottery::class,'auction_id');
    }
}
