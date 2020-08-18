<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class Brand extends EloquentModel
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='brand';

    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }
}
