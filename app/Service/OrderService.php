<?php


namespace App\Service;


use App\Exceptions\ApiException;
use App\Models\Order;
use App\Models\Parking;
use DB;

class OrderService
{

    public static function createOrder($data)
    {
        $parking_id = $data['parking_id'];
        $parkings = new Parking();
        $parking = $parkings->getModelByIdOrFail($parking_id,['house']);
        $house = $parking->house;
        throw_unless($house,ApiException::class,'楼盘不存在');
        throw_if($house->house_status==20,ApiException::class,'该楼盘已下架');
        //车位类型
        $type_id = $parking->type_id;

        //获取价格
        try {
            DB::beginTransaction();
            $orders = new Order();
            $out_trade_no = $orders->uniqueOutTradeNo();

            OrderPayService::createPayOrder($out_trade_no,);
            $orders->create();

        }catch (\Exception $exception){
            \Log::error('下单有误',['exception'=>$exception]);
            return false;
        }
    }

    public static function getParking($parking_id,$with)
    {
        $parkings = new Parking();
        $parking = $parkings->getModelByIdOrFail($parking_id,$with);
        $house = $parking->house;
        throw_unless($house,ApiException::class,'楼盘不存在');
        throw_if($house->house_status==20,ApiException::class,'该楼盘已下架');
        return $parking;
    }
}
