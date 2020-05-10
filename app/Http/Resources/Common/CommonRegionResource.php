<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonRegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'region_id' => (integer)$this->region_id,
            'name' => (string)$this->name,
            'short_name' => (string)$this->short_name,
            'merger_name' => (string)$this->merger_name,
            'parent_id' => (integer)$this->parent_id,
            'level' => (integer)$this->level,
            'pinyin' => (string)$this->pinyin,
            'code' => (string)$this->code,
            'zip_code' => (string)$this->zip_code,
            'first' => (string)$this->first,
            'lng' => (string)$this->lng,
            'lat' => (string)$this->lat,
        ];
    }
}
