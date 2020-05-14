<?php

namespace App\Listeners;

use App\Events\OrderPayed;
use App\Models\AuctionRecord;
use App\Models\Order;
use App\Models\OrderParking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPayed  $event
     * @return void
     */
    public function handle(OrderPayed $event)
    {
        $message = $event->getMessage();
        $orders = new  Order();
        $out_trade_no = $message['out_trade_no'];
        /**
         * @var Order $order
         */
        $order = $orders->where('pay_status',10)->orderNo($out_trade_no)->with(['order_parking'])->first();

        //设置付款成功状态
        $order->update(['pay_status'=>20,'pay_time'=>time()]);
        //
        $order_parking = $order->order_parking;
        //竞拍写入竞拍记录
        AuctionRecord::create();
    }
}
