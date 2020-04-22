<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Course extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = $this->category;
        return [
            'course_id'=>$this->course_id,
            'title'=>$this->title,
            'image_id'=>$this->image_id,
            'image_url'=>$this->image?$this->image->url:'',
            'category_id'=>$this->category_id,
            'category_name'=>$category?$category->name:'',
            'start_time'=>date("Y-m-d H:i",$this->start_time),
            'end_time'=>date("Y-m-d H:i",$this->end_time),
            'address'=>$this->address,
            'content'=>$this->content,

        ];
    }
}
