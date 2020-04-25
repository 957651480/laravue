<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CourseCollection;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    public function __construct(Course $courses,Request $request)
    {
        if($mine = $request->get('mine')&&$request->getPathInfo()=='/api/courses'){
            $this->middleware('auth:api');
        }
        $this->courses = $courses;
    }



    public function index(Request $request)
    {
        //
        $query = $this->courses->with(['category','image','teacher.image'])->newQuery();
        $wheres =$this->filter($request);
        if($mine = $request->get('mine'))
        {
            $user_id = $request->user()->id;
            $query->whereHas('attend',function ($query)use($user_id){
                $query->where('user_id', '=', $user_id);
            });
        }
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
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
        $data = Arr::only($form,['title','category_id','content','image_id','address','times','number','teacher_id']);
        $this->courses->create($data);
        return $this->renderSuccess();
    }


    public function show($id)
    {
        //
        $course = $this->courses->with(['category','image','teacher.image'])->where('course_id',$id)->first();
        $course = new \App\Http\Resources\Course($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $course_id = Arr::pull($form,'course_id');

        $course = $this->courses->where('course_id',$course_id)->first();
        $data = Arr::only($form,['title','content','image_id','address','times','number','teacher_id']);
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

    public function export(Request $request)
    {
        $tileArray=['课程ID','标题','分类名称','教师名称','报名人数','课程人数','地点'];
        $query = $this->courses->with(['category','image','teacher.image'])->newQuery();
        $wheres =$this->filter($request);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $courseCol = $query->get();
        $transform = $courseCol->transform(function ($course)
        {
            $category = $course->category;
            $teacher = $course->teacher;
            return [
                'course_id' => (integer)$course->course_id,
                'title'=>$course->title,
                'category_name' => $category ? (string)$category->name : '',
                'teacher_name' => $teacher ? (string)$teacher->name : '',
                'attend_number' => (integer)$course->attend_number,
                'number' => (integer)$course->number,
                'address' => (string)$course->address,
            ];
        });
        return export_excel('kkk.csv',$tileArray,$transform->toArray());
    }



    protected function filter(Request $request)
    {
        $wheres =[];
        if($keyword = $request->get('keyword')){
            $this->filterTitleLike($keyword);
        }
        if($category_id = $request->get('category_id')){
            $wheres[]=$this->filterCategoryId($category_id);
        }
        return $wheres;
    }
    protected function filterTitleLike($title)
    {
        return ['title','like',"%{$title}%"];
    }
    protected function filterCategoryId($id)
    {
        return ['category_id','=',$id];
    }
}
