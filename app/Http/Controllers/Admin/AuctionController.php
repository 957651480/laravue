<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminAuctionResource;
use App\Models\Auction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuctionController extends Controller
{
    /**
     * @var Auction
     */
    protected $service;

    /**
     * AuctionController constructor.
     * @param Auction $service
     */
    public function __construct(Auction $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $query = $this->service->with(['images','city','author'])->newQuery();
        $wheres =$this->filter($request);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->orderByDesc('sort')->latest('created_at')->paginate($request->get('limit'));

        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminAuctionResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        DB::transaction(function ()use($request)
        {
            list($data,$images) = $this->validateAuction($request->all());
            $model = $this->service->create($data);
            $model->images()->sync($images);
        });
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','city','author']);
        $course = new AdminAuctionResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        list($data,$images) = $this->validateAuction($request->all());
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


    protected function validateAuction($from)
    {
        $rules=[
            'title'=>'required',
            'desc'=>'sometimes',
            'content'=>'required',
            'images'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'sort'=>'sometimes',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'name.required'=>'楼盘名称必填',
                'desc.sometimes'=>'标题必填',
                'content.required'=>'详情必填',
                'images.required'=>'图片必传',
                'start_time.required'=>'开始时间必传',
                'end_time.required'=>'结束时间必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $data['start_time']=strtotime($data['start_time']);
        $data['end_time']=strtotime($data['end_time']);
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
