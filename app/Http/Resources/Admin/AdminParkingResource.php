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
        $author = $this->author;
        return [
            'parking_id'=>(integer)$this->parking_id,
            'title'=>(string)$this->title,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
