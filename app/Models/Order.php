<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='order';
    protected $primaryKey='order_id';


    /**
     * @return string
     * @throws \Exception
     */
    public static function uniqueOutTradeNo()
    {
        $prefix = date('Ymd');
        for ($i = 0; $i < 18; $i++) {
            // 随机生成 6 位的数字，并创建订单号
            $no = $prefix.random_int(100000, 999999).substr(microtime(true),-4);
            // 判断是否已经存在
            if (!static::query()->where('out_trade_no', $no)->exists()) {
                return $no;
            }
        }
    }
}
