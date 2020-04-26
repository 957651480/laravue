<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ApiException extends \Exception
{
    //
    public function render(Request $request)
    {
        return response()->json([
            'code'=>$this->getCode(),
            'msg' => $this->getMessage(),
            'data' => new \stdClass(),
        ]);
    }
}
