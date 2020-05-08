<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $city = $this->city;
        $author = $this->author;
        $parking_area = $this->parking_area;
        $parking_floor = $this->parking_floor;
        return [
            'parking_id'=>(integer)$this->parking_id,
            'code'=>(string)$this->code,
            'desc'=>(string)$this->desc,
            'content' => (string)$this->content,
            'type_id' => (integer)$this->type_id,
            'size_id' => (integer)$this->size_id,
            'price'=>(float)$this->price,
            'parking_area_id'=>(integer)$this->parking_area_id,
            'parking_area_name'=>(string)optional($parking_area)->name,
            'parking_floor_id'=>(integer)$this->parking_floor_id,
            'parking_floor_name'=>(string)optional($parking_floor)->name,
            'house_id'=>(string)$this->house_id,
            'house_name'=>(string)optional($this->house)->name,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
