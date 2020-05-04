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
        $keyword = Arr::getStringTrimAddSlashes($form,'keyword');
        $city_id = Arr::getInt($form,'city_id');

        $paginate = $this->service->likeTitle($keyword)->cityId($city_id)
            ->latest('created_at')->with(['images','region','city','author'])
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
            list($data,$images) = $this->validateHouse($request->all());
            $model = $this->service->create($data);
            $model->images()->sync($images);
        });
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','region','city','author']);
        $course = new AdminHouseResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        list($data,$images) = $this->validateHouse($request->all());
        $model = $this->service->getModelByIdOrFail($id);
        //
        DB::transaction(function ()use($model,$data,$images)
        {
            $model->images()->sync($images);
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
            'house_region'=>'required',
            'images'=>'required',
            'content'=>'required',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'name.required'=>'楼盘名称必填',
                'desc.sometimes'=>'标题必填',
                'house_region.required'=>'区域必填',
                'images.required'=>'图片必传',
                'content.required'=>'详情必须',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $data['region_id'] =$data['house_region'][2];
        $images = Arr::pull($data,'images');
        return [$data,$images];
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
