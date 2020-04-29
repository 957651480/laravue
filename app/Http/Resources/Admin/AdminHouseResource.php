<?php

namespace App\Http\Resources\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminHouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $attended =0;
        $time_id = [];
        /**
         * @var Collection $attends
         */
        $attends = $this->attends;
        $user = $request->user();
        if($user&&$attends){
            $attend = $attends->first(function ($attend)use($user){
                return $attend->user_id==$user->id;
            });
            if($attend){
                $attended=1;
                $time_id = $attend->time_id;
            }
        }

        $teacher_image_url=[];
        $category = $this->category;
        if($teacher = $this->teacher){
            $teacher_image_url = $teacher->images->map(function ($image){
                return $image->file->url;
            });
        }

        return [
            'course_id' => (integer)$this->course_id,
            'title' => (string)$this->title,
            'desc' => (string)$this->desc,
            'image_id' => (integer)$this->image_id,
            'image_url' => (string)$this->image ? $this->image->url : '',
            'category_id' => (integer)$this->category_id,
            'category_name' => $category ? (string)$category->name : '',
            'teacher_id' => (integer)$this->teacher_id,
            'teacher_name' => $teacher ? (string)$teacher->name : '',
            'teacher_position' => $teacher ? (array)$teacher->position : '',
            'teacher_image_url' => $teacher_image_url,
            'date'=>$this->date,
            'time_id'=>$time_id,
            'times' => $this->times,
            'attended'=>$attended,
            'attend_number' => (integer)$this->attend_number,
            'number' => (integer)$this->number,
            'address' => (string)$this->address,
            'content' => (string)$this->content,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }
}
