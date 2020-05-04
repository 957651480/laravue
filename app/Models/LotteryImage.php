<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\LotteryImage
 *
 * @property int $lottery_id
 * @property int $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $file
 * @property-read int|null $file_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lottery[] $lottery
 * @property-read int|null $lottery_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
