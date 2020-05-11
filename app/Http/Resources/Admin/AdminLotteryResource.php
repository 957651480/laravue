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

        $city = $this->city;
        $author = $this->author;
        return [
            'lottery_id'=>(integer)$this->lottery_id,
            'title'=>(string)$this->title,
            'desc'=>(string)$this->desc,
            'start_time' => (string)date('Y-m-d H:i:s',$this->start_time),
            'end_time' => (string)date('Y-m-d H:i:s',$this->end_time),
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'content'=>(string)$this->content,
            'status_id'=>(integer)$this->status_id,
            'status_name' => (string)$this->statusName(),
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
