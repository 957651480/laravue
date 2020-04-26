<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Course extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $attended=0;
        $user = $request->user();
        if($user&&$this->attend){
            $user_id = $user->id;
            if($this->attend->user_id==$user_id){
                $attended=1;
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
