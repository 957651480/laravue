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
        $paginate = $query->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new CategoryCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['name']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $category_id = Arr::pull($form,'category_id');

        $category = $this->categories->where('category_id',$category_id)->first();
        $data = Arr::only($form,['name',]);
        $category->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $category = $this->categories->where('category_id',$id)->first();
        $category->delete();
        return $this->renderSuccess();
    }
}
