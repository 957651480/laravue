<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminParkingFloorResource extends JsonResource
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
        return [
            'parking_floor_id'=>(integer)$this->parking_floor_id,
            'name'=>(string)$this->name,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
