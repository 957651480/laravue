<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminBannerResource extends JsonResource
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
            'type_id'=>(integer)$this->type_id,
            'image_id'=>(integer)$this->image_id,
            'images'=>[$image],
            'show'=>(integer)$this->show,
            'sort'=>(integer)$this->sort,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
