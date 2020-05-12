<?php


namespace App\Http\Controllers;


use App\Events\OrderPayed;
use App\Exceptions\ApiException;
use App\Http\Resources\Api\ApiUserResource;
use App\Models\User;
use Arr;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'display_name'=>$userInfo['nickName'],
            'open_id'=>$openid,
            'avatar'=>$userInfo['avatarUrl'],
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
        $response = app()->handlePaidNotify(function ($message, $fail) use($request){
            // 你的逻辑
            try {
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
        $data['user_info'] = json_decode($data['user_info'], true);
        return [$data['code'],$data['user_info']];
    }
}
