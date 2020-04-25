<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherCollection;
use App\Teacher;
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
        $query = $this->teachers->newQuery();
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
        $data = Arr::only($form,['name','position','introduction','image_id']);
        $this->teachers->create($data);
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
        $teacher = $this->teachers->where('teacher_id',$id)->firstOrFail();
        $data = Arr::only($form,['name','position','introduction','image_id']);
        $teacher->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $teacher = $this->teachers->where('teacher_id',$id)->firstOrFail();
        $teacher->delete();
        return $this->renderSuccess();
    }
}
