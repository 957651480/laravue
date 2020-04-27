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


    public function images()
    {
        return $this->hasMany(TeacherImage::class,'teacher_id');
    }

    public function getPositionAttribute($value)
    {
        return json_decode($value,true);
    }

    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = json_encode($value,true);
    }
}
