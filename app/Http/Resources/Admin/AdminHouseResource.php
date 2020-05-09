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
        $parking_images = $this->parking_images;
        return [
            'house_id' => (integer)$this->house_id,
            'name' => (string)$this->name,
            'desc' => (string)$this->desc,
            'household' => (integer)$this->household,
            'sales'=>$this->sales,
            'map'=>$this->map,
            'house_recommend'=>$this->house_recommend,
            'house_region'=>$this->house_region,
            'parking_count'=>$this->parking_count,
            'region_id'=>$this->region_id,
            'region_merger_name'=>(string)optional($region)->merger_name,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'parking_images' => $parking_images->fileIds(),
            'parking_image_list' => $parking_images,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'content' => (string)$this->content,
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),

        ];
    }
}
