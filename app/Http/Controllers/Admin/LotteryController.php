<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminLotteryResource;
use App\Models\Lottery;
use Arr;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    /**
     * @var Lottery
     */
    protected $lotterys;

    /**
     * LotteryController constructor.
     * @param Lottery $lotterys
     */
    public function __construct(Lottery $lotterys)
    {
        $this->lotterys = $lotterys;
    }


    public function index(Request $request)
    {
        //
        $query = $this->lotterys->with(['images.file'])->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('name','like',"%{$keyword}%");
        }
        $paginate = $query->latest()->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminLotteryResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['name','position','introduction']);
        $lottery = $this->lotterys->create($data);
        if($images = Arr::get($form,'images')){
            $images_key_ids = array_map(function ($item){
                return Arr::only($item,'file_id');
            },$images);
            $lottery->images()->createMany($images_key_ids);
        }
        return $this->renderSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $lottery = $this->lotterys->with(['images.file'])->where('lottery_id',$id)->firstOrFail();
        $data = Arr::only($form,['name','position','introduction']);
        $lottery->update($data);
        if($images = Arr::get($form,'images')){
            foreach ($lottery->images as $index => $image) {
                $image->delete();
            }
            $images_key_ids = array_map(function ($item){
                return Arr::only($item,'file_id');
            },$images);
            $lottery->images()->createMany($images_key_ids);
        }
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $lottery = $this->lotterys->where('lottery_id',$id)->firstOrFail();
        $lottery->delete();
        foreach ($lottery->images as $index => $image) {
            $image->delete();
        }
        return $this->renderSuccess();
    }
}
