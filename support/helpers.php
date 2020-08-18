<?php

if (! function_exists('api_response')) {

    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \App\Http\Response\ApiResponse|\Illuminate\Http\JsonResponse
     */
    function api_response(array $response=[])
    {
        $factory = app(\App\Http\Response\ApiResponse::class);
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->json($response);
    }
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

function array_get_int($arr,$key,$default=0){
    return Arr::getInt($arr,$key,$default);
}

function getUserCityId(){
    $city_id =0;
    if($user = auth()->user()){
        $city_id=$user->city_id;
    }
    return $city_id;
}
