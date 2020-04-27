<?php

namespace App\Http\Controllers;

use App\File;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * @var File
     */
    protected $files;
    //

    /**
     * FileController constructor.
     * @param File $files
     */
    public function __construct(File $files)
    {
        $this->files = $files;
    }

    public function upload(Request $request)
    {
        $uploadFile = $request->file('file');
        if(!$uploadFile->isValid()){
            return $this->renderError('文件有误');
        }
        $ext = $uploadFile->getClientOriginalExtension();
        //获取文件的绝对路径
        $path = $uploadFile->getRealPath();
        //定义文件名
        $format = sprintf("%s%s",date('Y-m-d-h-i-s'),$uploadFile->getClientOriginalName());
        $filename = md5($format).'.'.$ext;
        $dir = sprintf("%s/%s/",$ext,date('Y/m'));
        if(!Storage::disk('uploads')->exists($dir)){
            Storage::disk('uploads')->makeDirectory($dir);
        }
        Storage::disk('uploads')->put($dir.$filename, file_get_contents($path));
        /**
         * @var File $file
         */
        $file = $this->files->create([
                'filename'=>$dir.$filename,
                'source_filename'=>$uploadFile->getClientOriginalName(),
                'extension'=>$uploadFile->getClientOriginalExtension(),
                'mime_type'=>$uploadFile->getClientMimeType(),
                'size'=>$uploadFile->getSize()
        ]);
        $file->name=$file->source_filename;
        return $file->setVisible(['file_id','filename','name','url']);
    }
}
