<?php

namespace App\Http\Controllers;

use App\Attend;
use App\Http\Resources\AttendCollection;
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
