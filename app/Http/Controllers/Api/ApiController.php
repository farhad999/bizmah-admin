<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    function __construct()
    {

    }

    function unauthorized()
    {
        return response()->json([
            'message' => "Have not enough permission",
        ]);

    }


    function responseWithData(array $data): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            array_merge(['status' => 'success'], $data)
        );
    }

    function responseWithSuccess($msg="Operation successful")
    {
        return response()->json([
            'status' => 'success',
            'message' => $msg
        ], 200);
    }

    function responseWithFailed($msg = "Unable to perform action")
    {
        return response()->json([
            'status' => 'failed',
            'message' => $msg
        ]);
    }

    function respondWithError($message=null, $status_code = 500)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message ? $message : 'Something went wrong!'
        ], $status_code)->send();
    }

    public function handleException($e)
    {
        $error = "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage();
        \Log::emergency($error);
        return response()->json([
            'status' => 'Error',
            'message' => 'Something went wrong!',
            'error_message' => $error
        ], 500);
    }

    public function otherExceptions($e)
    {
        $msg = is_object($e) ? $e->getMessage() : ($e ? $e : 'Bad request');
        return $this->respondWithError($msg);

    }

}
