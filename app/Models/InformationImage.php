<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\InformationImage
 *
 * @property int $information_id
 * @property int $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $file
 * @property-read int|null $file_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\House[] $house
 * @property-read int|null $house_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage whereInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InformationImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InformationImage extends Pivot
{
    //
    protected $guarded = [];
    protected $table='information_image';
    protected $primaryKey='information_image_id';


    public function file()
    {
        return $this->belongsToMany(File::class,'image_id');
    }

    public function house()
    {
        return $this->belongsToMany(House::class,'information_id');
    }
}
