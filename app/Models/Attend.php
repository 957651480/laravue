<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Attend
 *
 * @property int $attend_id 报名id
 * @property string $student_name 学生姓名
 * @property string $grade 年级
 * @property string $class 班级
 * @property string $time_id 时间段id
 * @property int $course_id 课程id
 * @property int $user_id 用户id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\House $course
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attend onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereAttendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereStudentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereTimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attend withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attend withoutTrashed()
 * @mixin \Eloquent
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attend whereCityId($value)
 */
class Attend extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='attend';
    protected $primaryKey='attend_id';

    public function course()
    {
        return $this->belongsTo(House::class,'course_id');
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
