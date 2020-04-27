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
        $image = $this->images->map(function ($image){
            return $image->file;
        });
        return [
            'teacher_id'=>(integer)$this->teacher_id,
            'name'=>(string)$this->name,
            'position'=>(array)$this->position,
            'images'=>$image,
            'introduction'=>(string)$this->introduction,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
