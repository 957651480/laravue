<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiAuctionResource;
use App\Models\Auction;
use App\Models\AuctionRecord;
use App\Service\OrderService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuctionController extends Controller
{
    /**
     * @var Auction
     */
    protected $service;

    /**
     * AuctionController constructor.
     * @param Auction $service
     */
    public function __construct(Auction $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        //
        $query = $this->service->with(['parking','images','city','author'])->newQuery();
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $title = Arr::getStringTrimAddSlashes($form,'title');
        $city_id = Arr::getInt($form,'city_id');

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
            'list'=>ApiAuctionResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }



    public function show(Request $request,$id)
    {
        //
        $course = $this->service->getModelByIdOrFail($id,['parking','images','city','author']);
        $course = new ApiAuctionResource($course);
        return $this->renderSuccess('',$course);
    }


    public function auction(Request $request)
    {
        $user = $request->user();
        $form = $request->all();
        list($data,$auction,$parking)= $this->validateAuction($form);
        //查询最高价
        $price = $data['price'];
        $high_price = $this->getHighPrice($auction)?:$auction->price;
        if($price<$high_price){
            return $this->renderError('竞拍不能小于当前起拍价');
        }
        $where = ['user_id'=>$user->id,'auction_id'=>$auction->auction_id];
        //不存在记录就返回二维码给前端
        if($record = AuctionRecord::where($where)->first()){
            list($order,$payment) = OrderService::createOrder($parking,$user);
            $data=[
                'order'=>$order,
                'payment'=>$payment
            ];
            return $this->renderSuccess('',$data);
        }
        $record->increment('increase_price',$price-$high_price);
        $this->renderSuccess('竞拍成功');
    }

    protected function getHighPrice(Auction $auction)
    {
        $high_price = AuctionRecord::where('auction_id',$auction->auction_id)
            ->orderByDesc('increase_price')->value(DB::raw('price+increase_price'));
        return $high_price;
    }


    protected function validateAuction($from)
    {
        $rules=[
            'auction_id'=>'required',
            'price'=>'required',
        ];
        $validator = \Validator::make($from,$rules,
            [
                'auction_id.required'=>'竞拍参数未传必填',
                'price.required'=>'价格必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->validated();
        $auction_id = $data['auction_id'];
        //
        if(!$auction = $this->service->getModelById($auction_id)){
            throw new ApiException('转盘不存在');
        }
        if($auction->status_id!==20){
            throw new ApiException(sprintf("竞拍%s",$auction->statusName()));
        }
        $current_time = time();

        if($current_time<$auction->start_time){
            throw new ApiException('活动未开始');
        }
        if($current_time>$auction->end_time){
            throw new ApiException('活动已结束');
        }
        $parking = OrderService::getParking($auction->parking_id);
        $house = OrderService::getParkingHouseOrFail($parking);
        OrderService::validateHouseStatus($house);

        return [$data,$auction,$parking];
    }
}
