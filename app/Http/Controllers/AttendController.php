<?php

namespace App\Http\Controllers;

use App\Attend;
use App\Course;
use App\Exceptions\ApiException;
use App\Http\Resources\AttendCollection;
use Arr;
use DB;
use Illuminate\Http\Request;

class AttendController extends Controller
{
    /**
     * @var Attend
     */
    protected $attends;

    /**
     * AttendController constructor.
     * @param Attend $attends
     */
    public function __construct(Attend $attends)
    {
        $this->attends = $attends;
    }


    public function index(Request $request)
    {
        //
        $query = $this->attends->with(['course','user'])->newQuery();
        if($keyword = $request->get('keyword')){
            $query->where('title','like',"%{$keyword}%");
        }
        $paginate = $query->latest()->paginate($request->get('limit'));
        $data =[
            'total'=>$paginate->total(),
            'list'=>new AttendCollection($paginate)
        ];
        return $this->renderSuccess('',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

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
    }


    public function destroy($id)
    {
        //
        $attend = $this->attends->where('attend_id',$id)->firstOrFail();
        $attend->delete();
        return $this->renderSuccess();
    }

    public function join(Request $request,$id)
    {

        $post = $request->all();
        $rules=[
            'student_name'=>'required',
            'grade'=>'required',
            'class'=>'required',
            'time_id'=>'required',
        ];
        $validator = \Validator::make($post,$rules,[
            'student_name.required'=>'学生姓名必填',
            'grade.required'=>'年级必填',
            'class.required'=>'班级必填',
            'time_id.required'=>'请选择时间段',
        ]);
        if($validator->fails()){
            return $this->renderError($validator->messages()->first());
        }
        $data = Arr::only($post,array_keys($rules));
        $user_id = $request->user()->id;
        DB::transaction(function () use($id,$data,$user_id){

            //判断课程人数是否已经满了
            $courseWheres =[
                ['course_id','=',$id],
                ['attend_number','<','number'],
            ];
            $course = Course::where($courseWheres)->sharedLock()->first();
            throw_unless($course,ApiException::class,'该课程报名人数已满');


            //判断自己是否有报过该课程
            $attendWheres =[
                ['course_id','=',$id],
                ['user_id','=',$user_id],
            ];
            $attend = $this->attends->where($attendWheres)->sharedLock()->first();
            throw_if($attend,ApiException::class,'你已经报名了该课程');
            //报名
            $data = array_merge($data,[
                'course_id'=>$id,
                'user_id'=>$user_id
            ]);
            $this->attends->create($data);
            $course->increment('attend_number');
        }, 2);
        return $this->renderSuccess('报名成功');
    }
}
