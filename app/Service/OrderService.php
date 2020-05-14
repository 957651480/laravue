<?php


namespace App\Service;


use App\Exceptions\ApiException;
use App\Models\House;
use App\Models\Order;
use App\Models\OrderParking;
use App\Models\Parking;
use App\Models\User;
use DB;
use Str;

class OrderService
{

    /**
     * OrderService constructor.
     */
    public function __construct()
    {
    }

    public static function createOrder(Parking $parking,User $user)
    {
        $house = $parking->house;
        //车位类型
        $type_id = $parking->type_id;
        //获取价格

        $orders = new Order();
        $order_no = $orders->uniqueOutTradeNo();
        $payment = OrderPayService::createPayOrder($order_no,0.01,$user->open_id);
        $attributes=[
            'user_id'=>$user->id,
            'order_source'=>$type_id,
            'order_no'=>$order_no
        ];
        $order = $orders->create($attributes);
        OrderParking::create([
            'order_id'=>$order->order_id,
            'house_id'=>$house->house_id,
            'house_name'=>$house->name,
            'city_id'=>$parking->city_id,
        ]);
        return [$order,$payment];
    }

    public static function getParking($parking_id,$with=[])
    {
        $parkings = new Parking();
        $parking = $parkings->getModelById($parking_id,$with);
        throw_unless($parking,ApiException::class,'车位不存在');
        return $parking;
    }


    public static function getParkingHouseOrFail(Parking $parking)
    {
        $house = $parking->house;
        throw_unless($house,ApiException::class,'车位所属楼盘不存在');
        return $house;
    }


    public static function validateHouseStatus(House $house)
    {
         throw_if($house->house_status==20,ApiException::class,'该楼盘已下架');
    }
}
