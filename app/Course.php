<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @property int $course_id 课程id
 * @property string $title 标题
 * @property string $content 详细介绍
 * @property int $image_id 文件id
 * @property int $start_time 开始时间
 * @property int $end_time 开始时间
 * @property string $address 地点
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\File $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 */
class Course extends Model
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $primaryKey='course_id';


    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function getTimesAttribute($value)
    {
        return json_decode($value,true);
    }

    public function setTimesAttribute($value)
    {
        $this->attributes['times'] = json_encode($value,true);
    }
}
