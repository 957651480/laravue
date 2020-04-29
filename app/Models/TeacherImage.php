<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\TeacherImage
 *
 * @property int $teacher_image_id id
 * @property int $teacher_id id
 * @property int $file_id id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\File $file
 * @property-read \App\Models\Teacher $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage whereTeacherImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TeacherImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
