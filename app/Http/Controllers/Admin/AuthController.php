<?php
/**
 * File AuthController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (!Auth::attempt($credentials)) {
            return $this->renderError('用户账号或密码有误');
        }
        $user = $request->user();
        $token = $user->createToken('api');
        $user->token = $token->plainTextToken;
        return $this->renderSuccess('',new UserResource($user),['Authorization'=>$token->plainTextToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->renderSuccess();
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    public function wxLogin(Request $request)
    {
        $form = $request->all();
        $rules=[
            'code'=>'required',
            'user_info'=>'required',
        ];
        $validator = \Validator::make($form,$rules,[
            'code.required'=>'授权码必须',
            'user_info'=>'用户信息必须',
        ]);
        if($validator->fails()){
            return $this->renderError($validator->messages()->first());
        }

        $code = Arr::get($form,'code');
        $wechat = config('wechat');
        $session = $this->sessionKey($code,$wechat['app_id'],$wechat['secret']);
        if (isset($session['errcode']))
        {
            return $this->renderError($session['errmsg']);
        }
        // 自动注册用户
        $refereeId = isset($form['referee_id']) ? $form['referee_id'] : null;
        $userInfo = json_decode($form['user_info'], true);
        //查询openId
        $openid = Arr::get($session,'openid');
        $userData = Arr::only($userInfo,['nickName','avatarUrl']);
        $users = new User();
        if(!$user = $users->where('open_id',$openid)->first())
        {
            $user = new User();
            $user->open_id = $openid;
            $user->name = $openid;
            $user->nickName = $userData['nickName'];
            $user->avatarUrl = $userData['avatarUrl'];
            $user->save();
        }
        //登录
        Auth::loginUsingId($user->id);
        $user = $request->user();
        $token = $user->createToken('api');
        $user->token = $token->plainTextToken;
        return $this->renderSuccess('',new UserResource($user));
    }


    /**
     * 获取session_key
     * @param $code
     * @return array|mixed
     */
    public function sessionKey($code,$appid,$secret)
    {
        /**
         * code 换取 session_key
         * ​这是一个 HTTPS 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。
         * 其中 session_key 是对用户数据进行加密签名的密钥。为了自身应用安全，session_key 不应该在网络上传输。
         */
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $response = Http::withoutVerifying()->get($url, [
            'appid' => $appid,
            'secret' => $secret,
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ])->json();
        return $response;
    }
}
