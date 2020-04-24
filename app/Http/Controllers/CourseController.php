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
    public function __construct(Course $courses)
    {
        $this->middleware('auth:api')->only(['store','update','destroy','export']);
        $this->courses = $courses;
    }


    public function index(Request $request)
    {
        //
        $query = $this->courses->with(['category','image','teacher.image'])->newQuery();
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

        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        return $this->export_excel('demo.csv');
    }


    /**
     * 数据导出到excel(csv文件)
     * @param $fileName
     * @param array $tileArray
     * @param array $dataArray
     */
    function export_excel($fileName, $tileArray = [], $dataArray = [])
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $fileName);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));// 转码 防止乱码(比如微信昵称)
        fputcsv($fp, $tileArray);
        $index = 0;
        foreach ($dataArray as $item) {
            if ($index == 1000) {
                $index = 0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp, $item);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}
