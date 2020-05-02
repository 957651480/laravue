<?php

namespace App\Http\Resources\Admin;

use App\Collection\FileCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminHouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var FileCollection $images
         */
        $city = $this->city;
        $author = $this->author;
        $region = $this->region;
        return [
            'house_id' => (integer)$this->house_id,
            'name' => (string)$this->name,
            'desc' => (string)$this->desc,
            'household' => (integer)$this->household,
            'house_region'=>$this->house_region,
            'region_id'=>$this->region_id,
            'region_merger_name'=>(string)optional($region)->merger_name,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'content' => (string)$this->content,
            'created_at'=>(string)$this->created_at->toDateTimeString(),
            'updated_at'=>(string)$this->updated_at->toDateTimeString(),

        ];
    }
}
