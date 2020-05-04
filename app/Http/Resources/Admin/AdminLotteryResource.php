<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminLotteryResource extends JsonResource
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
            'lottery_id'=>(integer)$this->lottery_id,
            'name'=>(string)$this->name,
            'position'=>(array)$this->position,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'introduction'=>(string)$this->introduction,
            'created_at'=>(string)optional($this->created_at),
            'updated_at'=>(string)optional($this->updated_at),
        ];
    }
}
