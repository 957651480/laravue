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
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);
        $keyword = Arr::getStringTrimAddSlashes($form,'keyword');
        $city_id = Arr::getInt($form,'city_id');
        if($user_city_id = getUserCityId()){
            $city_id = $user_city_id;
        }
        $query = $this->service->newQuery();
        $paginate = $query->likeName($keyword)->cityId($city_id)
            ->latest('created_at')->with(['city','author'])
            ->paginate($limit);
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
                'name.required'=>'车位楼层名称必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        return $data;
    }
}
