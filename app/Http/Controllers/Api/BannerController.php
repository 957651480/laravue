<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiBannerResource;
use App\Models\Banner;
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
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $query = $this->banners->newQuery();
        if($title = Arr::getStringTrimAddSlashes($form,'title')){
            $query->where('name','like',"%$title%");
        }

        $paginate = $query->orderByDesc('sort')->latest()->paginate($limit);
        $data =ApiBannerResource::collection($paginate);
        return api_response()->success(['total'=>$paginate->total(),'data'=>$data]);
    }



    public function show($id)
    {
        //
        $banner = $this->banners->getModelByIdOrFail($id,['image']);
        return $this->renderSuccess('',new ApiBannerResource($banner));
    }
}
