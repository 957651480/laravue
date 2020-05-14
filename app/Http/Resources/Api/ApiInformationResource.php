<?php

namespace App\Http\Resources\Api;

use App\Collection\FileCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiInformationResource extends JsonResource
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
        $city = $this->city;
        $author = $this->author;
        return [
            'information_id' => (integer)$this->information_id,
            'title' => (string)$this->title,
            'desc' => (string)$this->desc,
            'information_recommend' => (integer)$this->information_recommend,
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'images' => $this->images->fileIds(),
            'image_list' => $this->images,
            'content' => (string)$this->content,
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),

        ];
    }
}
