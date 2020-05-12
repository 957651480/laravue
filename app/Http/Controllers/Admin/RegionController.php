<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminRegionResource;
use App\Http\Resources\Admin\AdminRegionResourceCollection;
use App\Models\Region ;
use Arr;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * @var Region
     */
    protected $service;

    /**
     * RegionController constructor.
     * @param Region $service
     */
    public function __construct(Region $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);


        $query = $this->service->newQuery();
        if($request->has('parent_id')){
            $query->whereParentId($request->get('parent_id'));
        }
        if($name = Arr::getStringTrimAddSlashes($form,'name')){
            $query->likeName($name);
        }
        if($level = $request->get('level')){
            $query->level($level);
        }
        $paginator = $query->paginate($limit);
        $data =[
            'total'=>$paginator->total(),
            'list'=>AdminRegionResource::collection($paginator)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        $form = $request->all();
        $data = $this->validateRegion($form);
        $this->service->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        //
        $region = new AdminRegionResource($this->service->getModelByIdOrFail($id));
        return $this->renderSuccess('',$region);
    }


    public function update(Request $request, $id)
    {
        //
        $form = $request->all();
        $region = $this->service->getModelByIdOrFail($id);
        $data = $this->validateRegion($form);
        $region->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $region = $this->service->getModelByIdOrFail($id);
        $region->delete();
        return $this->renderSuccess();
    }

    protected function validateRegion($form)
    {
        $validator = \Validator::make($form,[
            'name'=>'required',
            'parent_id'=>'sometimes',
            'level'=>'sometimes',
            'pinyin'=>'sometimes',
            'short_name'=>'sometimes',
            'merger_name'=>'sometimes',
            'code'=>'sometimes',
            'zip_code'=>'sometimes',
            'first'=>'sometimes',
        ],[
            'name.required'=>'名称必须'
        ]);
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        return $validator->validated();
    }

    public function treeList(Request $request)
    {
        $need_level = $request->get('need_level',0);
        $all_region = $this->service->fetchLevelAll($need_level);
        $parent_id = $request->get('parent_id');
        if($city_id=getUserCityId()){
            $parent_id=$city_id;
        }
        $list = $this->service->getTree($all_region,$parent_id);
        return $this->renderSuccess('',['list'=>$list]);
    }
}
