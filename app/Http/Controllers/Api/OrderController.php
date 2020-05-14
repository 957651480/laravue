<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\House;
use App\Exceptions\ApiException;
use App\Http\Resources\Api\ApiOrderResource;
use App\Models\Parking;
use App\Service\OrderPayService;
use App\Service\OrderService;
use Arr;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var Order
     */
    protected $orders;

    /**
     * AttendController constructor.
     * @param Order $orders
     */
    public function __construct(Order $orders)
    {
        $this->orders = $orders;
    }


    public function index(Request $request)
    {
        //
        $query = $this->orders->with(['course','user'])->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('title','like',"%{$keyword}%");
        }
        $paginate = $query->latest()->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>ApiOrderResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
        $attend = $this->attends->where('attend_id',$id)->firstOrFail();
        $attend->delete();
        return $this->renderSuccess();
    }

    protected function validateOrder($from){

        $rules=[
            'parking_id'=>'required',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'parking_id.required'=>'车位必选',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->validated();
        return $data;
    }
}
