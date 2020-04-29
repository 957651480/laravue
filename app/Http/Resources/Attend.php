<?php

namespace App\Http\Resources;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class Attend extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->user;
        $times=[];
        if($course = $this->course){
            $times = Arr::only($course->times,$this->time_id);
        }

        return [
            'attend_id'=>(integer)$this->attend_id,
            'grade'=>$this->grade,
            'class'=>$this->class,
            'student_name'=>$this->student_name,
            'times'=>$times,
            'user_id'=>$this->user_id,
            'user_nickName'=>$user->nickName??'',
            'user_avatarUrl'=>$user->avatarUrl??'',
            'course_id'=>$this->course_id,
            'course_title'=>$course->title??'',
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
