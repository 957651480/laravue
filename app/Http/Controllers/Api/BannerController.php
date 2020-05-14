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

        $title = Arr::getStringTrimAddSlashes($form,'title');
        $city_id = Arr::getInt($form,'city_id');

        $query = $this->banners->with(['image','city','author'])->newQuery();
        if($scopes =array_filter([
            'cityId'=>$city_id,
            'likeTitle'=>$title
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $paginate = $query->orderByDesc('sort')->latest()->paginate($limit);
        $data =[
            'total'=>$paginate->total(),
            'list'=>ApiBannerResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }



    public function show($id)
    {
        //
        $banner = $this->banners->getModelByIdOrFail($id,['image']);
        return $this->renderSuccess('',new ApiBannerResource($banner));
    }








}
