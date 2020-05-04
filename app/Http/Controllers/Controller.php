<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function renderError($msg='成功',$data=[])
    {
        empty($data)&&$data=new \stdClass();
        return response()->json([
            'code'=>0,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    public function renderSuccess($msg='成功',$data=[],$header=[])
    {
        empty($data)&&$data=new \stdClass();
        return response()->json([
            'code'=>200,
            'msg'=>$msg,
            'data'=>$data
        ])->withHeaders($header);
    }

    public function getUserCityId(User $user)
    {
        return $user->city_id;
    }
}
