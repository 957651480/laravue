<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ApiBrandResource;
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
        $limit = Arr::getInt($form, 'limit', 15);

        $query = $this->brands->newQuery();

        if ($name = Arr::getStringTrimAddSlashes($form, 'name')) {
            $query->where('name', 'like', "%$name%");
        }

        $paginate = $query->orderByDesc('sort')->latest()->paginate($limit);
        $data = ApiBrandResource::collection($paginate);
        return api_response()->success(['total' => $paginate->total(), 'data' => $data]);
    }

    public function show($id)
    {
        //
    }
}
