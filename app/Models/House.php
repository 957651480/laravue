<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class House extends EloquentModel
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $primaryKey='house_id';


    public function images()
    {
        return $this->belongsTo(File::class,'image_id');
    }

    public function attends()
    {
        return $this->hasMany(Attend::class,'course_id');
    }

}
