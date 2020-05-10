<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\CommonRegionResource;
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
        $level = Arr::getInt($form,'level');
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
            'list'=>CommonRegionResource::collection($paginator)
        ];
        return $this->renderSuccess('',$data);
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

    public function city(Request $request)
    {
        $list = $this->service->fetchAllCity();
        $data = [
            'hot_city'=>[],
            'list'=>$list,
            'total'=>count($list)
        ];
        return $this->renderSuccess('',$data);
    }
}
