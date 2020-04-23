<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Banner extends JsonResource
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
            'banner_id'=>(integer)$this->banner_id,
            'title'=>(string)$this->title,
            'image_id'=>(integer)$this->image_id,
            'image_url'=>(string)$image->url??'',
            'show'=>(integer)$this->show,
            'sort'=>(integer)$this->sort,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
