<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateParking($request);
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


    protected function validateParking(Request $request)
    {
        return $this->validate($request,[
            'title'=>'required',
            'show'=>'sometimes',
            'sort'=>'sometimes'
        ],
            [
                'title.required'=>'标题必填',
            ]
        );
    }
}
