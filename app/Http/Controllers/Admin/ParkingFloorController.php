<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminParkingFloorResource;
use App\Models\ParkingFloor;
use Arr;
use Illuminate\Http\Request;

class ParkingFloorController extends Controller
{
    /**
     * @var ParkingFloor
     */
    protected $service;

    /**
     * ParkingFloorController constructor.
     * @param ParkingFloor $service
     */
    public function __construct(ParkingFloor $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $query = $this->service->newQuery();
        $paginate = $query->latest()->with(['city','author'])
            ->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminParkingFloorResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateParkingFloor($request->all());
        $this->service->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        $model = $this->service->getModelByIdOrFail($id,['city','author']);
        return $this->renderSuccess('',new AdminParkingFloorResource($model));
    }


    public function update(Request $request, $id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $data = $this->validateParkingFloor($request->all());
        $model->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $model = $this->service->getModelByIdOrFail($id);
        $model->delete();
        return $this->renderSuccess();
    }


    protected function validateParkingFloor($form)
    {
        $rules = [
            'name'=>'required',
        ];
        $validator = \Validator::make($form,$rules,
            [
                'code.name'=>'车位区域名称必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        return $data;
    }
}
