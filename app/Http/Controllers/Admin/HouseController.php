<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Filter\HouseFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminHouseResource;
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
        if($user_city_id = getUserCityId()){
            $city_id = $user_city_id;
        }
        $query = $this->service->newQuery()->orderByDesc('sort')->latest('created_at');

        $query->leftJoin('parking','parking.house_id','house.house_id');
        $paginate = $query->select(['house.*',DB::raw('count(parking.parking_id) as parking_count')])
            ->likeName($name)->cityId($city_id)
            ->groupBy('house.house_id')
            ->with(['images','parking_images','region','city','author'])
            ->paginate($limit);

        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminHouseResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        DB::transaction(function ()use($request)
        {
            list($data,$images,$parking_images) = $this->validateHouse($request->all());
            $model = $this->service->create($data);
            $model->images()->sync($images);
            $model->parking_images()->sync($parking_images);
        });
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','parking_images','region','city','author']);
        $course = new AdminHouseResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        list($data,$images,$parking_images) = $this->validateHouse($request->all());
        $model = $this->service->getModelByIdOrFail($id);
        //
        DB::transaction(function ()use($model,$data,$images,$parking_images)
        {
            $model->images()->sync($images);
            $model->parking_images()->sync($parking_images);
            $model->update($data);
        });
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $model->delete();
        return $this->renderSuccess();
    }


    protected function validateHouse($from)
    {
        $rules=[
            'name'=>'required',
            'desc'=>'sometimes',
            'household'=>'required',
            'sales'=>'required|array',
            'map'=>'required|array',
            'rate'=>'required',
            'house_status'=>'sometimes',
            'house_region'=>'required',
            'images'=>'required',
            'parking_images'=>'required',
            'content'=>'required',
            'house_recommend'=>'required',
            'sort'=>'sometimes',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'name.required'=>'楼盘名称必填',
                'desc.sometimes'=>'标题必填',
                'rate.required'=>'车位配比必填',
                'house_status.sometimes'=>'楼盘上下架状态',
                'house_region.required'=>'区域必填',
                'images.required'=>'图片必传',
                'parking_images.required'=>'车位分布图必传',
                'content.required'=>'详情必须',
                'household.required'=>'住户数必填',
                'sales.required'=>'销售数据必填',
                'map.required'=>'楼盘数据必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $data['region_id'] = end($data['house_region']);
        $images = Arr::pull($data,'images');
        $parking_images = Arr::pull($data,'parking_images');
        return [$data,$images,$parking_images];
    }

    protected function filter(Request $request)
    {
        $wheres =[];
        if($keyword = $request->get('keyword')){
            $this->filterTitleLike($keyword);
        }
        if($category_id = $request->get('category_id')){
            $wheres[]=$this->filterCategoryId($category_id);
        }
        return $wheres;
    }
}
