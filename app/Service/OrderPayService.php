<?php


namespace App\Service;
use App\Exceptions\ApiException;
use EasyWeChat\Factory;
use function EasyWeChat\Kernel\Support\generate_sign;

class OrderPayService
{


    /**
     * @param $out_trade_no
     * @param $total_fee
     * @param $openid
     * @param string $body
     * @param string $notify_url
     * @return array
     * @throws ApiException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createPayOrder($out_trade_no, $total_fee, $openid, $body='', $notify_url='')
    {
        $attributes =[
            'body' => $body?$body:'微信支付',
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee*100,
            'notify_url' => $notify_url?$notify_url:url('api/wechat/notify',null,true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $openid,
        ];
        $payment = \EasyWeChat::payment();
        $config = $payment->getConfig();

        $app = Factory::payment($config);
        $result = $app->order->unify($attributes);

        if($result['return_code'] !== 'SUCCESS'){
            throw new ApiException($result['return_msg']);
        }
        // 如果成功生成统一下单的订单，那么进行二次签名
        $params = [
            'appId'     => $config['app_id'],
            'timeStamp' => time(),
            'nonceStr'  => $result['nonce_str'],
            'package'   => 'prepay_id=' . $result['prepay_id'],
            'signType'  => 'MD5',
        ];
        // config('wechat.payment.default.key')为商户的key
        $params['paySign'] = generate_sign($params, $config['key']);
        return $params;
    }

    /**
     * @param $transaction_id
     * @param $refund_number
     * @param $total_fee
     * @param $refund_fee
     * 订单退款
     */
    public static function refundPayOrder($transaction_id,$refund_number,$total_fee,$refund_fee)
    {

    }
}
