<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Teacher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->image;
        return [
            'teacher_id'=>(integer)$this->teacher_id,
            'name'=>(string)$this->name,
            'position'=>(array)$this->position,
            'image_id'=>(integer)$this->image_id,
            'image_url'=>(string)$image->url??'',
            'introduction'=>(string)$this->introduction,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
