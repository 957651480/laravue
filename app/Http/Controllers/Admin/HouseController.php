<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminHouseResource;
use App\Models\House;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HouseController extends Controller
{
    /**
     * @var House
     */
    protected $service;

    /**
     * CourseController constructor.
     * @param House $house
     */
    public function __construct(House $house)
    {
        $this->service = $house;
    }


    public function index(Request $request)
    {
        //
        $query = $this->service->with([])->newQuery();
        $wheres =$this->filter($request);

        $query->when($wheres,function ($query)use($wheres){
            $query->where($wheres);
        });
        $paginate = $query->latest('created_at')->paginate($request->get('limit'));

        $data =[
            'total'=>$paginate->total(),
            'list'=>AdminHouseResource::collection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    public function store(Request $request)
    {
        //
        $form  = $request->all();
        $data = Arr::only($form,['title','desc','category_id','content','address','date','times','number','teacher_id']);
        $this->service->create($data);
        return $this->renderSuccess();
    }


    public function show(Request $request,$id)
    {
        //
        $query = $this->service->with(['category','image','teacher.images.file'])->newQuery();

        $user = $request->user();
        $query->when($user,function ($query)use($user){
            $query->leftJoin('attends','attends.course_id','courses.course_id');
        });
        $course = $query->where('courses.course_id',$id)->firstOrFail();
        $course = new AdminHouseResource($course);
        return $this->renderSuccess('',$course);
    }


    public function update(Request $request, $id)
    {
        //
        $form  = $request->all();
        $course_id = Arr::pull($form,'course_id');

        $course = $this->service->where('course_id',$course_id)->firstOrFail();
        $data = Arr::only($form,['title','desc','content','address','date','times','number','teacher_id']);
        $course->update($data);
        return $this->renderSuccess();
    }


    public function destroy($id)
    {
        //
        $course = $this->service->with(['attends'])->where('course_id',$id)->firstOrFail();
        DB::transaction(function ()use($course){
            $course->delete();
            foreach ($course->attends as $attend){
                $attend->delete();
            }
        });

        return $this->renderSuccess();
    }


    public function export(Request $request)
    {
        $tileArray=['课程ID','标题','分类名称','教师名称','报名人数','课程人数','地点'];
        $query = $this->service->with(['category'])->newQuery();
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
