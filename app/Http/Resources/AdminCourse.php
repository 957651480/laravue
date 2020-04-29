<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {



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
            'attend_number' => (integer)$this->attend_number,
            'number' => (integer)$this->number,
            'address' => (string)$this->address,
            'content' => (string)$this->content,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }
}
