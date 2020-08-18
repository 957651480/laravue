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
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $title = Arr::getStringTrimAddSlashes($form,'title');

        $query = $this->banners->with(['image'])->newQuery();
        if($scopes =array_filter([
            'likeTitle'=>$title
        ])){
            foreach (($scopes) as $scope => $value) {
                $query->$scope($value);
            }
        }
        $paginate = $query->orderByDesc('sort')->latest()->paginate($limit);
        $data =AdminBannerResource::collection($paginate);
        return api_response()->success(['total'=>$paginate->total(),'data'=>$data]);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateBanner($request->all());
        $this->banners->create($data);
        return api_response()->success();
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
        return api_response()->success();
    }


    public function destroy($id)
    {
        //
        $banner = $this->banners->getModelByIdOrFail($id);
        $banner->delete();
        return api_response()->success();
    }

    public function batchDelete(Request $request)
    {
        $this->banners->whereIn('id',$request->get('ids'))->delete();
        return api_response()->success();
    }

    protected function validateBanner($from)
    {
        $rules =[
            'title'=>'required',
            'image_id'=>'required',
            'show'=>'sometimes',
            'sort'=>'sometimes'
        ];
        $validator = \Validator::make($from,$rules,
            [
                'title.required'=>'标题必填',
                'image_id.required'=>'图片必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        return $validator->validated();
    }
}
