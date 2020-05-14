<?php

namespace App\Http\Resources\Api;

use App\Collection\FileCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiHouseResource extends JsonResource
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
        $images = $this->images;
        $city = $this->city;
        $author = $this->author;
        $region = $this->region;
        /**
         * @var FileCollection $parking_images
         */
        $parking_images = $this->parking_images;
        /**
         * @var Collection $appointments
         */
        $appointments = $this->appointments;
        $user_has_appoint = 0;
        $user = $request->user();
        if($user&&$appointments->isNotEmpty()){
            $first = $appointments->first(function ($appointment)use($user){
               return  $appointment->user_id==$user->id;
            });
            if($first){
                $user_has_appoint=1;
            }
        }
        return [
            'house_id' => (integer)$this->house_id,
            'name' => (string)$this->name,
            'desc' => (string)$this->desc,
            'household' => (integer)$this->household,
            'sales'=>$this->sales,
            'map'=>$this->map,
            'parking_count'=>(integer)$this->parking_count,
            'parking_avg'=>number_format($this->parking_avg,2),
            'appoint_count'=>(integer)$this->appoint_count,
            'appointment'=>$appointments,
            'user_has_appoint'=>$user_has_appoint,
            'region_id'=>$this->region_id,
            'region_merger_name'=>(string)optional($region)->merger_name,
            'image_list' => $images->urls(),
            'parking_image_list' => $parking_images->urls(),
            'city_id'=>(integer)$this->city_id,
            'city_name'=>(string)optional($city)->name,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'content' => (string)$this->content,
            'house_recommend'=>$this->house_recommend,
            'sort' => (integer)$this->sort,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),

        ];
    }
}
