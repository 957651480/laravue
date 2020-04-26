<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attend extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $primaryKey='attend_id';

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getTimeIdAttribute($value)
    {
        return json_decode($value,true);
    }

    public function setTimeIdAttribute($value)
    {
        $this->attributes['time_id'] = json_encode($value,true);
    }
}
