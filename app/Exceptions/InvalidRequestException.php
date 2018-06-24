<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class InvalidRequestException extends Exception
{
    //
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request){
        if($request->expectsJson()){
            //json()方法的第二个参数就是 Http 的返回码
            return response()->json(['msg'=> $this->message], $this->code);
        }

        return view('pages.error',['msg'=> $this->message]);
    }
}
