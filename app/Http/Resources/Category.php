<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'category_id'=>(integer)$this->category_id,
            'name'=>(string)$this->name,
            'sort'=>(integer)$this->sort,
            'created_at'=>(string)optional($this->created_at),
            'updated_at'=>(string)optional($this->updated_at),
        ];
    }
}
