<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Resources\BannerCollection;
use Arr;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * @var Banner
     */
    protected $banners;

    /**
     * BannerController constructor.
     * @param Banner $banners
     */
    public function __construct(Banner $banners)
    {
        $this->banners = $banners;
    }


    public function index(Request $request)
    {
        //
        $query = $this->banners->newQuery();
        $paginate = $query->with(['image'])->latest()
            ->orderBy('sort','desc')
            ->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new BannerCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data =Arr::only($form,['title','image_id','show','sort']);
        $this->banners->create($data);
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
        $banner = $this->banners->where('banner_id',$id)->firstOrFail();
        $form  = $request->all();
        $data =Arr::only($form,['title','image_id','show','sort']);
        $banner->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $banner = $this->banners->where('banner_id',$id)->firstOrFail();
        $banner->delete();
        return $this->renderSuccess();
    }
}
