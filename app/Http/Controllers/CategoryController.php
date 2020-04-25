<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryCollection;
use Arr;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    protected $categories;

    /**
     * CategoryController constructor.
     * @param Category $categories
     */
    public function __construct(Category $categories)
    {
        $this->categories = $categories;
    }


    public function index(Request $request)
    {
        //
        /**
         * @var \Illuminate\Database\Eloquent\Builder $query
         */
        $query = $this->categories->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('name','like',"%{$keyword}%");
        }
        $paginate = $query->latest()->orderBy('sort','desc')->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new CategoryCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }



    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['name','sort']);
        $this->categories->create($data);
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


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $category = $this->categories->where('category_id',$id)->firstOrFail();
        $data = Arr::only($form,['name','sort']);
        $category->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $category = $this->categories->where('category_id',$id)->firstOrFail();
        $category->delete();
        return $this->renderSuccess();
    }
}
