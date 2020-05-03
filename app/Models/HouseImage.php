<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HouseImage extends Pivot
{
    //
    protected $guarded = [];
    protected $table='house_image';
    protected $primaryKey='house_image_id';


    public function file()
    {
        return $this->belongsToMany(File::class,'image_id');
    }

    public function house()
    {
        return $this->belongsToMany(House::class,'house_id');
    }
}
