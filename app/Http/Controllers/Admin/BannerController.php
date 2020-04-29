<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\Admin\AdminBannerResourceCollection;
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
            'list'=>new AdminBannerResourceCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateBanner($request);
        $this->banners->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
        $banner = $this->banners->getModelByIdOrFail($id);
        $data = $this->validateBanner($request);
        $banner->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $banner = $this->banners->getModelByIdOrFail($id);
        $banner->delete();
        return $this->renderSuccess();
    }

    protected function validateBanner(Request $request)
    {
        return $this->validate($request,[
            'title'=>'required',
            'type_id'=>'required',
            'image_id'=>'required',
            'show'=>'sometimes',
            'sort'=>'sometimes'
            ],
            [
            'title.required'=>'标题必填',
            'type_id.required'=>'类型必填',
            'image_id.required'=>'图片必传',
            ]
        );
    }
}
