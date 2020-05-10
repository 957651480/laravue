<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminLotteryPrizeResource extends JsonResource
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
        $image = $this->image;
        return [
            'lottery_prize_id'=>(integer)$this->lottery_prize_id,
            'name'=>(string)$this->name,
            'grade'=>(string)$this->grade,
            'number'=>(string)$this->number,
            'probability'=>(string)$this->probability,
            'lottery_id'=>(integer)$this->lottery_id,
            'lottery_title'=>(string)optional($this->lottery)->title,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'images' => [$this->image_id],
            'image_list' => [$image],
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
