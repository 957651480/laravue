<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $primaryKey='teacher_id';


    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }
}
