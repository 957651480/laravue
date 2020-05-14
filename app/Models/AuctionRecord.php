<?php

namespace App\Models;

use App\Filter\AuctionFilter;
use App\Scopes\CityScope;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class AuctionRecord extends EloquentModel
{
    use SoftDeletes,CityTrait,AuthorTrait,AuctionFilter;
    //
    protected $guarded = [];
    protected $table='auction';
    protected $primaryKey='auction_id';




    public function images()
    {
        return $this->belongsToMany(File::class,'auction_image','auction_id','image_id');
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class,'parking_id');
    }

    public function statusName()
    {
        $status_names=[
            10=>'未开始',
            20=>'进行中',
            30=>'已结束',
        ];
        return $status_names[$this->status_id]??'';
    }
}
