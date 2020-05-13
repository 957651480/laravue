<?php


namespace App\Service;


use App\Exceptions\ApiException;
use App\Models\House;
use App\Models\Order;
use App\Models\Parking;
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

    public static function createOrder(Parking $parking)
    {
        //车位类型
        $type_id = $parking->type_id;
        //获取价格
        try {
            DB::beginTransaction();
            $orders = new Order();
            $order_no = $orders->uniqueOutTradeNo();
            $payment = OrderPayService::createPayOrder($order_no,1.01,'oGimR4ifs1vzQhyzI4XTCvmSBf0E');
            $attributes=[
                'user_id'=>1,
                'order_no'=>$order_no
            ];
            $order = $orders->create($attributes);

        }catch (\Exception $exception){
            \Log::error('下单有误',['exception'=>$exception]);
            return false;
        }
        return $payment;
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
