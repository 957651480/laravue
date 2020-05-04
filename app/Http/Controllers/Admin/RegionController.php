<?php

namespace App\Http\Controllers\Admin;

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
        $limit = (integer)$request->get('limit',15);
        $query = $this->service->newQuery();
        $query->when($request->has('parent_id'),function ($query)use($request){
            $query->whereParentId($request->get('parent_id'));
        });
        if($level = $request->get('level')){
            $query->where('level',$level);
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
        $data = $this->validateRegion($request);
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
        $region = $this->service->getModelByIdOrFail($id);
        $data = $this->validateRegion($request);
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

    protected function validateRegion(Request $request)
    {
        return $this->validate($request,[
            'name'=>'required',
            'parent_id'=>'sometimes',
            'level'=>'sometimes',
            'pinyin'=>'sometimes',
            'show'=>'sometimes',
            'sort'=>'sometimes'
        ]
        );
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
