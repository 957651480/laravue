<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiHouseResource;
use App\Models\House;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HouseController extends Controller
{
    /**
     * @var House
     */
    protected $service;

    /**
     * CourseController constructor.
     * @param House $house
     */
    public function __construct(House $house)
    {
        $this->service = $house;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $name = Arr::getStringTrimAddSlashes($form,'name');
        $city_id = Arr::getInt($form,'city_id');
        $hs_as = 'hs';
        $query = $this->service->setTable($hs_as)->from('house',$hs_as)->newQuery()
            ->orderByDesc("sort")->latest("created_at");


        $query->from('house',$hs_as);
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeName'=>$name
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $query->leftJoin('parking','parking.house_id',"{$hs_as}.house_id");
        $query->leftJoin('house_appointment','house_appointment.house_id',"{$hs_as}.house_id");
        $paginate = $query->select(["{$hs_as}.*",
            DB::raw('count(parking.parking_id) as parking_count'),
            DB::raw('avg(parking.price) as parking_avg'),
            DB::raw('count(house_appointment.house_appointment_id) as appoint_count'),
        ])
            ->groupBy("{$hs_as}.house_id")
            ->with(['images','parking_images','region','city','author','appointments'])
            ->paginate($limit);

        $data =[
            'total'=>$paginate->total(),
            'list'=>ApiHouseResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }




    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','parking_images','region','city','author']);
        $course = new ApiHouseResource($course);
        return $this->renderSuccess('',$course);
    }

}
