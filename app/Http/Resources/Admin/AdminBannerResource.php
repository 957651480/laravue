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
        $city = $this->city;
        $author = $this->author;
        return [
            'banner_id'=>(integer)$this->banner_id,
            'title'=>(string)$this->title,
            'type_id'=>(integer)$this->type_id,
            'image_id'=>(integer)$this->image_id,
            'images'=>[$this->image_id],
            'image_list'=>[$image],
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'show'=>(integer)$this->show,
            'sort'=>(integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
