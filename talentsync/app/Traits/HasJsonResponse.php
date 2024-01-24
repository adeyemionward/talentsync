<?php
namespace App\Traits;
use Illuminate\Http\Request;

trait HasJsonResponse
{
    function jsonResponse($status = false, $status_code = false, $message = false, $data = false, $token = false, $debug = false)
    {
        return response()->json(array(
            'status' => $status ? $status : false,
            'status_code' => $status_code ? $status_code : 200,
            'message' => $message ? $message : null,
            'data' =>  $data,
            'token' => $token ? $token : null,
            'debug' => $debug ? $debug : null
        ), $status_code ? $status_code : 200);
    }

}
