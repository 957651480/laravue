<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiInformationResource;
use App\Models\Information;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InformationController extends Controller
{
    /**
     * @var Information
     */
    protected $service;

    /**
     * InformationController constructor.
     * @param Information $service
     */
    public function __construct(Information $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $title = Arr::getStringTrimAddSlashes($form,'title');
        $city_id = Arr::getInt($form,'city_id');

        $query = $this->service->with(['images','city','author'])->newQuery();
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeTitle'=>$title
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $paginate = $query->orderByDesc('sort')->latest('created_at')->paginate($limit);

        $data =[
            'total'=>$paginate->total(),
            'list'=>ApiInformationResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }




    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','city','author']);
        $course = new ApiInformationResource($course);
        return $this->renderSuccess('',$course);
    }








}
