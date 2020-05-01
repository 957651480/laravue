<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminParkingResource;
use App\Models\Parking;
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
        $query = $this->service->newQuery();
        $paginate = $query->latest()
            ->paginate($request->get('limit'));
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
        $model = $this->service->getModelByIdOrFail($id);
        return $this->renderSuccess('',new AdminParkingResource($model));
    }


    public function update(Request $request, $id)
    {
        $model = $this->service->getModelByIdOrFail($id);
        $data = $this->validateParking($request);
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

        $validator = \Validator::make($form,[
            'code'=>'required',
        ],
            [
                'code.required'=>'车位号必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        return $validator->getData();
    }
}
