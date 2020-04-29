<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherCollection;
use App\Models\Teacher;
use Arr;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * @var Teacher
     */
    protected $teachers;

    /**
     * TeacherController constructor.
     * @param Teacher $teachers
     */
    public function __construct(Teacher $teachers)
    {
        $this->teachers = $teachers;
    }


    public function index(Request $request)
    {
        //
        $query = $this->teachers->with(['images.file'])->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('name','like',"%{$keyword}%");
        }
        $paginate = $query->latest()->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new TeacherCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }


    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['name','position','introduction']);
        $teacher = $this->teachers->create($data);
        if($images = Arr::get($form,'images')){
            $images_key_ids = array_map(function ($item){
                return Arr::only($item,'file_id');
            },$images);
            $teacher->images()->createMany($images_key_ids);
        }
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
        $form  = $request->all();
        $teacher = $this->teachers->with(['images.file'])->where('teacher_id',$id)->firstOrFail();
        $data = Arr::only($form,['name','position','introduction']);
        $teacher->update($data);
        if($images = Arr::get($form,'images')){
            foreach ($teacher->images as $index => $image) {
                $image->delete();
            }
            $images_key_ids = array_map(function ($item){
                return Arr::only($item,'file_id');
            },$images);
            $teacher->images()->createMany($images_key_ids);
        }
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $teacher = $this->teachers->where('teacher_id',$id)->firstOrFail();
        $teacher->delete();
        foreach ($teacher->images as $index => $image) {
            $image->delete();
        }
        return $this->renderSuccess();
    }
}
