<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

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
