<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminInformationResource;
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
        if($user_city_id = getUserCityId()){
            $city_id = $user_city_id;
        }

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
            'list'=>AdminInformationResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        DB::transaction(function ()use($request)
        {
            list($data,$images) = $this->validateInformation($request->all());
            $model = $this->service->create($data);
            $model->images()->sync($images);
        });
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','city','author']);
        $course = new AdminInformationResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        list($data,$images) = $this->validateInformation($request->all());
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


    protected function validateInformation($from)
    {
        $rules=[
            'title'=>'required',
            'desc'=>'sometimes',
            'content'=>'required',
            'images'=>'required',
            'information_recommend'=>'sometimes',
            'sort'=>'sometimes',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'name.required'=>'楼盘名称必填',
                'desc.sometimes'=>'标题必填',
                'content.required'=>'详情必填',
                'images.required'=>'图片必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $images = Arr::pull($data,'images');
        return [$data,$images];
    }

}
