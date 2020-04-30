<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminRegionResource;
use App\Http\Resources\Admin\AdminRegionResourceCollection;
use App\Models\Region ;
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
        $query = $this->service->newQuery();
        $paginator = $query->paginate($request->get('limit'));
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
}
