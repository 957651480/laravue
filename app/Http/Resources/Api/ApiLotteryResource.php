<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiLotteryResource extends JsonResource
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
        $prizes = $this->prizes;
        foreach ($prizes as $index =>$prize) {

        }
        return [
            'lottery_id'=>(integer)$this->lottery_id,
            'title'=>(string)$this->title,
            'desc'=>(string)$this->desc,
            'start_time' => (string)date('Y-m-d H:i:s',$this->start_time),
            'end_time' => (string)date('Y-m-d H:i:s',$this->end_time),
            'prizes'=>$prizes,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'image_list' => $this->images->urls(),
            'content'=>(string)$this->content,
            'status_id'=>(integer)$this->status_id,
            'status_name' => (string)$this->statusName(),
            'lottery_recommend' => (integer)$this->lottery_recommend,
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
