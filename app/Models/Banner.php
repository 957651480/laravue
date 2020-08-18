<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends EloquentModel
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='banner';
    protected $primaryKey='id';

    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }
}
