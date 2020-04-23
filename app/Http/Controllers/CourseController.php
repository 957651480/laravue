<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CourseController extends Controller
{
    /**
     * @var Course
     */
    protected $courses;

    /**
     * CourseController constructor.
     * @param Course $courses
     */
    public function __construct(Course $courses)
    {
        $this->courses = $courses;
    }


    public function index(Request $request)
    {
        //
        $query = $this->courses->with(['category','image'])->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('title','like',"%{$keyword}%");
        }
        if($category_id = $request->get('category_id')){
            $query->where('category_id','=',$category_id);
        }
        $paginate = $query->latest()->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new CourseCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['title','category_id','content','image_id','address','start_time','end_time','number']);
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $this->courses->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        //
        $course = $this->courses->with(['image','category'])->where('course_id',$id)->first();
        $course = new \App\Http\Resources\Course($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $course_id = Arr::pull($form,'course_id');

        $course = $this->courses->where('course_id',$course_id)->first();
        $data = Arr::only($form,['title','content','image_id','address','start_time','end_time','number']);
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $course->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $course = $this->courses->where('course_id',$id)->first();
        $course->delete();
        return $this->renderSuccess();
    }
}
