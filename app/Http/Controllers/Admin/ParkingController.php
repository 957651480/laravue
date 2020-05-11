<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminParkingResource;
use App\Models\Parking;
use Arr;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * @var Parking
     */
    protected $service;

    /**
     * ParkingController constructor.
     * @param Parking $service
     */
    public function __construct(Parking $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $query = $this->service->newQuery()->orderByDesc('sort')->latest();
        $code = Arr::getStringTrimAddSlashes($form,'code');

        $type_id = Arr::getInt($form,'type_id');
        $size_id = Arr::getInt($form,'size_id');

        $city_id = Arr::getInt($form,'city_id');
        if($user_city_id = getUserCityId()){
            $city_id = $user_city_id;
        }
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeCode'=>$code,
            'typeId'=>$type_id,
            'sizeId'=>$size_id,
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $paginate = $query->with(['city','house','author'])
            ->paginate($limit);
        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminParkingResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateParking($request->all());
        $this->service->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        $model = $this->service->getModelByIdOrFail($id,['city','house','author']);
        return $this->renderSuccess('',new AdminParkingResource($model));
    }


    public function update(Request $request, $id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $data = $this->validateParking($request->all());
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


    protected function validateParking($form)
    {
        $rules = [
            'house_id'=>'required',
            'code'=>'required',
            'price'=>'required',
            'handsel'=>'required',
            'type_id'=>'required',
            'size_id'=>'required',
            'parking_area_id'=>'required',
            'parking_floor_id'=>'required',
            'sort'=>'sometimes',
        ];
        $validator = \Validator::make($form,$rules,
            [
                'house_id.required'=>'必须选择一个楼盘',
                'code.required'=>'车位编号必填',
                'price.required'=>'车位价格必填',
                'handsel.required'=>'车位定金必填',
                'type_id.required'=>'车位类型',
                'size_id.required'=>'车位尺寸',
                'parking_area_id.required'=>'车位区域必填',
                'parking_floor_id.required'=>'车位楼层必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        return $data;
    }
}
