<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\AdminCourse;
use App\Http\Resources\AdminCourseCollection;
use App\Http\Resources\CourseCollection;
use DB;
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
    public function __construct(Course $courses,Request $request)
    {
        if($token = $request->bearerToken()){
            $this->middleware('auth:api');
        }
        $this->courses = $courses;
    }


    public function adminIndex(Request $request)
    {
        $query = $this->courses->with(['category','image','teacher.images.file'])->newQuery();
        $wheres =$this->filter($request);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->latest()->paginate($request->get('limit'));

        $data =[
            'total'=>$paginate->total(),
            'list'=>new AdminCourseCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function adminShow(Request $request,$id)
    {
        //
        $query = $this->courses->with(['category','image','teacher.images.file'])->newQuery();
        $course = $query->where('course_id',$id)->firstOrFail();
        $course = new AdminCourse($course);
        return $this->renderSuccess('',$course);
    }


    public function index(Request $request)
    {
        //
        $query = $this->courses->with(['category','image','teacher.images.file'])->newQuery();
        $wheres =$this->filter($request);

        $user = $request->user();
        $query->when($user,function ($query)use($user){
            $query->leftJoin('attends','attends.course_id','courses.course_id');
        });
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->latest('courses.created_at')->paginate($request->get('limit'));

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
        $data = Arr::only($form,['title','desc','category_id','content','address','date','times','number','teacher_id']);
        $this->courses->create($data);
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $query = $this->courses->with(['category','image','teacher.images.file'])->newQuery();

        $user = $request->user();
        $query->when($user,function ($query)use($user){
            $query->leftJoin('attends','attends.course_id','courses.course_id');
        });
        $course = $query->where('courses.course_id',$id)->firstOrFail();
        $course = new \App\Http\Resources\Course($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $course_id = Arr::pull($form,'course_id');

        $course = $this->courses->where('course_id',$course_id)->firstOrFail();
        $data = Arr::only($form,['title','desc','content','address','date','times','number','teacher_id']);
        $course->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $course = $this->courses->with(['attends'])->where('course_id',$id)->firstOrFail();
        DB::transaction(function ()use($course){
            $course->delete();
            foreach ($course->attends as $attend){
                $attend->delete();
            }
        });

        return $this->renderSuccess();
    }

    public function myCourseList(Request $request)
    {
        $query = $this->courses->with(['category','teacher.images'])->newQuery();
        $wheres =$this->filter($request);
        $user = $request->user();
        $query->join('attends','attends.course_id','courses.course_id');
        $query->where('attends.user_id','=',$user->id);
        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->latest('courses.created_at')->paginate($request->get('limit'));

        $data =[
            'total'=>$paginate->total(),
            'list'=>new CourseCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function myCourseDetail(Request $request,$id)
    {
        $query = $this->courses->with(['category','teacher.images','attends']);
        $user = $request->user();
        $query->join('attends','attends.course_id','courses.course_id');
        $query->where('attends.user_id','=',$user->id);
        $course = $query->where('courses.course_id',$id)->firstOrFail();

        $course = new \App\Http\Resources\Course($course);
        return $this->renderSuccess('',$course);
    }

    public function export(Request $request)
    {
        $tileArray=['课程ID','标题','分类名称','教师名称','报名人数','课程人数','地点'];
        $query = $this->courses->with(['category'])->newQuery();
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
