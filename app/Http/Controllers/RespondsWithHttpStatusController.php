<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespondsWithHttpStatusController extends Controller
{
    /**
     * Return statusCode 200.
     * @param null|array|Model $data
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(Array $data, $status = 'success'){
        return response()->json([
            'status' => $status,
            'status_code' => Response::HTTP_OK,
            'data'  => $data
        ], Response::HTTP_OK);
    }

    /**
     * Return statusCode 200.
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseOk($message)
    {
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => $message
        ], Response::HTTP_OK);
    }

    /**
     * Return statusCode 201
     * @param null|array|Model $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseCreated(Array $data, $message = ''){
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_CREATED,
            'data'  => $data,
            'message' => $message
        ], Response::HTTP_CREATED);
    }

    /**
     * Return statusCode 422
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnProcess($message){
        return response()->json([
            'status' => 'fail',
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message'  => $message
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return statusCode 204
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNoContent(){
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
