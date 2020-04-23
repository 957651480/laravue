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
        $category = $this->category;
        $teacher = $this->teacher;
        return [
            'course_id' => (integer)$this->course_id,
            'title' => (string)$this->title,
            'image_id' => (integer)$this->image_id,
            'image_url' => (string)$this->image ? $this->image->url : '',
            'category_id' => (integer)$this->category_id,
            'category_name' => (string)$category ? $category->name : '',
            'teacher_id' => (integer)$this->teacher_id,
            'teacher_name' => (string)$teacher ? $teacher->name : '',
            'start_time' => (string)date("Y-m-d H:i", $this->start_time),
            'end_time' => (string)date("Y-m-d H:i", $this->end_time),
            'attend_number' => (integer)$this->attend_number,
            'number' => (integer)$this->number,
            'address' => (string)$this->address,
            'content' => (string)$this->content,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }
}
