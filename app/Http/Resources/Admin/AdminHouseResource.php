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

        return [
            'house_id' => (integer)$this->house_id,
            'name' => (string)$this->name,
            'desc' => (string)$this->desc,
            'household' => (integer)$this->household,
            'house_region'=>$this->house_region,
            'region_id'=>$this->region_id,
            'region_merger_name'=>$this->region->merger_name,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'address' => (string)$this->address,
            'content' => (string)$this->content,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }
}
