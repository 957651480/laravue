<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Teacher
 *
 * @property int $teacher_id 教师id
 * @property string $name 名字
 * @property string $position 职位
 * @property string $introduction 教师简介
 * @property int $image_id 文件id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherImage[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Teacher onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Teacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Teacher withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Teacher withoutTrashed()
 * @mixin \Eloquent
 */
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
