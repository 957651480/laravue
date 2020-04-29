<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherImage extends Pivot
{
    //
    protected $guarded = [];
    protected $primaryKey='teacher_image_id';
    protected $table='teacher_images';
    protected $appends=['url'];


    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class,'file_id');
    }

}
