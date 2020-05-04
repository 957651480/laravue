<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBannerResource;
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
        $paginate = $query->with(['image','city','author'])->latest()
            ->orderBy('sort','desc')
            ->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminBannerResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateBanner($request->all());
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
        $data = $this->validateBanner($request->all());
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

    protected function validateBanner($from)
    {
        $rules =[
            'title'=>'required',
            'type_id'=>'required',
            'images'=>'required',
            'show'=>'sometimes',
            'sort'=>'sometimes'
        ];
        $validator = \Validator::make($from,$rules,
            [
                'title.required'=>'标题必填',
                'type_id.required'=>'类型必填',
                'images.required'=>'图片必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = Arr::only($validator->getData(),array_keys($rules));
        $images = Arr::pull($data,'images');
        $data['image_id'] = $images[0];
        return $data;
    }
}
