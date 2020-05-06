<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\HouseImage
 *
 * @property int $house_id
 * @property int $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $file
 * @property-read int|null $file_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\House[] $house
 * @property-read int|null $house_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HouseParkingImage extends Pivot
{
    //
    protected $guarded = [];
    protected $table='house_parking_image';


    public function file()
    {
        return $this->belongsToMany(File::class,'image_id');
    }

    public function house()
    {
        return $this->belongsToMany(House::class,'house_id');
    }
}
