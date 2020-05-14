<?php


namespace App\Http\Controllers;


use App\Events\OrderPayed;
use App\Exceptions\ApiException;
use App\Http\Resources\Api\ApiUserResource;
use App\Models\User;
use App\Service\OrderPayService;
use App\Service\OrderService;
use Arr;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WechatController extends Controller
{


    public function login(Request $request)
    {
        $form = $request->all();
        list($code,$userInfo)=$this->validateLogin($form);

        $miniProgram = \EasyWeChat::miniProgram();
        $session = $miniProgram->auth->session($code);
        if(isset($session['errcode'])){
            throw new ApiException($session['errmsg']);
        }
        //查询openId
        $openid = Arr::get($session,'openid');
        $userData = [
            'display_name'=>$userInfo['nickName']??'',
            'open_id'=>$openid,
            'avatar'=>$userInfo['avatarUrl']??'',
        ];
        $user = User::firstOrCreate(['open_id'=>$openid],$userData)->refresh();
        //登录
        Auth::loginUsingId($user->id);
        $user = $request->user();
        $token = $user->createToken('api');
        $user->token = $token->plainTextToken;
        return $this->renderSuccess('',new ApiUserResource($user));
    }


    public function notify(Request $request)
    {
        $json = '{"appid":"wxe02410441ea47ba5","bank_type":"OTHERS","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1488033162","nonce_str":"5ebccf8514b39","openid":"oGimR4ifs1vzQhyzI4XTCvmSBf0E","out_trade_no":"202005149712280719","result_code":"SUCCESS","return_code":"SUCCESS","sign":"03CF57F3078F5633E9C8D52CF32B4981","time_end":"20200514125651","total_fee":"1","trade_type":"JSAPI","transaction_id":"4200000560202005142319139309"}';
        $message =json_decode($json,true);
        event(new OrderPayed($message));
        $payment = \EasyWeChat::payment();
        $response = $payment->handlePaidNotify(function ($message, $fail) use($request){
            // 你的逻辑
            $json = '{"appid":"wxe02410441ea47ba5","bank_type":"OTHERS","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1488033162","nonce_str":"5ebccf8514b39","openid":"oGimR4ifs1vzQhyzI4XTCvmSBf0E","out_trade_no":"202005149712280719","result_code":"SUCCESS","return_code":"SUCCESS","sign":"03CF57F3078F5633E9C8D52CF32B4981","time_end":"20200514125651","total_fee":"1","trade_type":"JSAPI","transaction_id":"4200000560202005142319139309"}';
            $message =json_decode($json,true);
            try {
                \Log::error('notify_message',$message);
                event(new OrderPayed($message));
                return true;
            }catch (\Exception $exception)
            {
                // 或者错误消息
                $fail('Order not exists.');
            }
        });

        return $response; // Laravel 里请使用：return $response;
    }

    public function order(Request $request)
    {
        $parking = OrderService::getParking(1);
        $house = OrderService::getParkingHouseOrFail($parking);
        OrderService::validateHouseStatus($house);
        $user = $request->user();
        list($order,$payment) = OrderService::createOrder($parking,$user);
        $data=[
            'order'=>$order,
            'payment'=>$payment
        ];
        return $this->renderSuccess('',$data);
    }

    protected function validateLogin($form)
    {
        $rules=[
            'code'=>'required',
            'user_info'=>'required',
        ];
        $validator = \Validator::make($form,$rules,[
            'code.required'=>'授权码必须',
            'user_info'=>'用户信息必须',
        ]);
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->validated();
        //$data['user_info'] = json_decode($data['user_info'], true);
        return [$data['code'],$data['user_info']];
    }
}
