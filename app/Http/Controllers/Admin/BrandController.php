<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBrandResource;
use App\Models\Brand;
use Arr;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * @var Brand
     */
    protected $brands;

    /**
     * BrandController constructor.
     * @param Brand $brands
     */
    public function __construct(Brand $brands)
    {
        $this->brands = $brands;
    }


    public function index(Request $request)
    {
        //
        $form = $request->all();
        $limit = Arr::getInt($form,'limit',15);

        $query = $this->brands->newQuery();

        if($name = Arr::getStringTrimAddSlashes($form,'name')){
            $query->where('name','like',"%$name%");
        }

        $paginate = $query->orderByDesc('sort')->latest()->paginate($limit);
        $data =AdminBrandResource::collection($paginate);
        return api_response()->success(['total'=>$paginate->total(),'data'=>$data]);
    }


    public function store(Request $request)
    {
        //
        $data = $this->validateBrand($request->all());
        $this->brands->create($data);
        return api_response()->success();
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
        $banner = $this->brands->getModelByIdOrFail($id);
        $data = $this->validateBrand($request->all());
        $banner->update($data);
        return api_response()->success();
    }


    public function destroy($id)
    {
        //
        $banner = $this->brands->getModelByIdOrFail($id);
        $banner->delete();
        return api_response()->success();
    }

    public function batchDelete(Request $request)
    {
        $this->brands->whereIn('id',$request->get('ids'))->delete();
        return api_response()->success();
    }

    protected function validateBrand($from)
    {
        $rules =[
            'name'=>'required',
            'image_id'=>'required',
            'show'=>'sometimes',
            'sort'=>'sometimes'
        ];
        $validator = \Validator::make($from,$rules,
            [
                'title.required'=>'名称必填',
                'image_id.required'=>'图片必传',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        return $validator->validated();
    }
}
