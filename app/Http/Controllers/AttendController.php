<?php

namespace App\Http\Controllers;

use App\Attend;
use App\Course;
use App\Http\Resources\AttendCollection;
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
        $this->middleware('auth:api')->only(['store','update','destroy']);
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
        $data = $request->validate([
            'course_id'=>'required',
        ],[
            'course_id.required'=>'请填写要报名的课程'
        ]);
        DB::transaction(function () use($data,$request){

            //判断课程人数是否已经满了
            $courseWheres =[
                ['course_id','=',$data['course_id']],
                ['attend_number','<','number'],
            ];
            $course = Course::where($courseWheres)->sharedLock()->first();
            throw_unless($course,\Exception::class,'该课程报名人数已满');

            $user = $request->user();
            //判断自己是否有报过该课程
            $attendWheres =[
                ['course_id','=',$data['course_id']],
                ['user_id','=',$user->id],
            ];
            $attend = $this->attends->where($attendWheres)->sharedLock()->first();
            throw_if($attend,\Exception::class,'你已经报名了该课程');
            //报名

            $this->attends->create([
                'course_id'=>$data['course_id'],
                'user_id'=>$user->id
            ]);
            $course->increment('attend_number');
        }, 2);
        return $this->renderSuccess('报名成功');
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
        $attend = $this->attends->where('attend_id',$id)->first();
        $attend->delete();
        return $this->renderSuccess();
    }
}
