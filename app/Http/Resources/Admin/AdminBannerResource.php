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
            'id'=>(integer)$this->banner_id,
            'title'=>(string)$this->title,
            'image_id'=>(integer)$this->image_id,
            'image_url'=>optional($this->image)->url,
            'image_list'=>[$image],
            'show'=>(integer)$this->show,
            'sort'=>(integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
