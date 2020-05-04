<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\AuctionImage
 *
 * @property int $auction_id
 * @property int $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lottery[] $auction
 * @property-read int|null $auction_count
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $file
 * @property-read int|null $file_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
