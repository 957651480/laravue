<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiLotteryResource;
use App\Models\Lottery;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LotteryController extends Controller
{
    /**
     * @var Lottery
     */
    protected $service;

    /**
     * LotteryController constructor.
     * @param Lottery $service
     */
    public function __construct(Lottery $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $title = Arr::getStringTrimAddSlashes($form,'title');
        $city_id = Arr::getInt($form,'city_id');

        $query = $this->service->with(['images','city','author'])->newQuery();
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeTitle'=>$title
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $paginate = $query->orderByDesc('sort')->latest('created_at')->paginate($limit);

        $data =[
            'total'=>$paginate->total(),
            'list'=>ApiLotteryResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['images','city','author']);
        $course = new ApiLotteryResource($course);
        return $this->renderSuccess('',$course);
    }

    public function draw(Request $request)
    {
        list($data,$lottery) = $this->validateLottery($request->all());

        $prizes = $this->getPrizes($lottery->prizes);
        foreach ($prizes as $key => $val) {
            $arr[$val['lottery_prize_id']] = $val['number'];//概率数组
        }
        $index = $this->get_rand($arr);
        if($index===null){
            return $this->renderError('未中奖');
        }
        //写入中奖记录
    }

    //计算中奖概率
    function get_rand($proArr) {
        $result = null;
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        // var_dump($proSum);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum); //返回随机整数

            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    public function getPrizes(Collection $prizes)
    {
        $prizes = $prizes->map(function (Model$prize){
            return $prize->setVisible(['lottery_prize_id','name','number']);
        });
        return $prizes;
    }


    protected function validateLottery($from)
    {
        $rules=[
            'lottery_id'=>'required',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'lottery_id.required'=>'未指定转盘',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->validated();
        $lottery_id = $data['lottery_id'];
        if(!$lottery = $this->service->getModelById($lottery_id,'prizes')){
            throw new ApiException('转盘不存在');
        }
        if($lottery->status_id!==20){
            throw new ApiException($lottery->statusName());
        }
        if(now()<$lottery->start_time){
            throw new ApiException('活动未开始');
        }
        if(now()>$lottery->end_time){
            throw new ApiException('活动已结束');
        }
        return [$data,$lottery];
    }

}
