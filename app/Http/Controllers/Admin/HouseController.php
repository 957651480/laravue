<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
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
        $query = $this->service->with(['images','region','city','author'])->newQuery();
        $wheres =$this->filter($request);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->latest('created_at')->paginate($request->get('limit'));

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


    public function export(Request $request)
    {
        $tileArray=['课程ID','标题','分类名称','教师名称','报名人数','课程人数','地点'];
        $query = $this->service->with(['category'])->newQuery();
        $wheres =$this->filter($request);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $courseCol = $query->get();
        $transform = $courseCol->transform(function ($course)
        {
            return [
                'course_id' => (integer)$course->course_id,
                'title'=>$course->title,
                'attend_number' => (integer)$course->attend_number,
                'number' => (integer)$course->number,
                'address' => (string)$course->address,
            ];
        });
        return export_excel('kkk.csv',$tileArray,$transform->toArray());
    }


    protected function validateHouse($from)
    {
        $validator = \Validator::make($from,[
            'name'=>'required',
            'desc'=>'sometimes',
            'house_region'=>'required',
            'images'=>'required',
        ],
            [
                'name.required'=>'楼盘名称必填',
                'desc.sometimes'=>'标题必填',
                'house_region.required'=>'区域必填',
                'images.required'=>'图片必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->getData();
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
    protected function filterTitleLike($title)
    {
        return ['title','like',"%{$title}%"];
    }
    protected function filterCategoryId($id)
    {
        return ['category_id','=',$id];
    }
}
