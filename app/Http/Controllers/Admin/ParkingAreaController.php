<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminParkingAreaResource;
use App\Models\ParkingArea;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ParkingAreaController extends Controller
{
    /**
     * @var ParkingArea
     */
    protected $service;

    /**
     * ParkingAreaController constructor.
     * @param ParkingArea $service
     */
    public function __construct(ParkingArea $service)
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
        $query = $this->service->newQuery()->latest('created_at');
        $scopes =array_filter([
            'cityId'=>$city_id,
            'likeName'=>$name
        ]);
        $query->when($scopes,function (Builder $query,$scopes){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        });
        $paginate = $query->with(['city','author'])->paginate($limit);
        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminParkingAreaResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateParkingArea($request->all());
        $this->service->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        $model = $this->service->getModelByIdOrFail($id,['city','author']);
        return $this->renderSuccess('',new AdminParkingAreaResource($model));
    }


    public function update(Request $request, $id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $data = $this->validateParkingArea($request->all());
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


    protected function validateParkingArea($form)
    {
        $rules = [
            'name'=>'required',
        ];
        $validator = \Validator::make($form,$rules,
            [
                'name.required'=>'车位区域名称必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        return $data;
    }
}
