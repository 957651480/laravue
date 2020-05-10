<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminLotteryRecordResource;
use App\Models\LotteryRecord;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LotteryRecordController extends Controller
{
    /**
     * @var LotteryRecord
     */
    protected $service;

    /**
     * LotteryRecordController constructor.
     * @param LotteryRecord $service
     */
    public function __construct(LotteryRecord $service)
    {
        $this->service = $service;
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
        $query = $this->service->with(['image','city','author','lottery'])->newQuery();
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeName'=>$name
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        if($lottery_name = Arr::getStringAddSlashes($form,'lottery_name'))
        {
            $query->whereHas('lottery',function (Builder $query)use($lottery_name){
                $query->where('name', 'like', "%{$lottery_name}%");
            });
        }

        $paginate = $query->orderByDesc('sort')->latest('created_at')->paginate($limit);

        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminLotteryRecordResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        $data = $this->validateLotteryRecord($request->all());
        $model = $this->service->create($data);

        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['image','city','author']);
        $course = new AdminLotteryRecordResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        $data= $this->validateLotteryRecord($request->all());
        $model = $this->service->getModelByIdOrFail($id);
        //
        $model->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $model->delete();
        return $this->renderSuccess();
    }



    protected function validateLotteryRecord($from)
    {
        $rules=[
            'lottery_id'=>'required',
            'name'=>'required',
            'grade'=>'required',
            'images'=>'required',
            'number'=>'required',
            'probability'=>'required',
            'sort'=>'sometimes',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'lottery_id.required'=>'转盘名称必填',
                'name.required'=>'名称必填',
                'grade.required'=>'详情必填',
                'images.required'=>'图片必传',
                'number.required'=>'数量必传',
                'probability.required'=>'概率必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $images = Arr::pull($data,'images');
        $data['image_id'] = $images[0];
        return $data;
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
