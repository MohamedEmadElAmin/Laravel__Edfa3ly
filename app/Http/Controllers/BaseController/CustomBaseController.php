<?php


namespace App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomBaseController extends Controller
{
    public function sendResponse($params = []): JsonResponse
    {
        $message = array_key_exists('message' , $params) ? $params['message'] : NULL;
        $data = array_key_exists('data' , $params) ? $params['data'] : NULL;
        $error = array_key_exists('error' , $params) ? $params['error'] : false;

        $code = 200;
        $response = ['success' => true];
        if($error)
        {
            $response = ['success' => false];
            $code = 404;
        }

        if($message)
            $response['message'] = $message;
        if($data)
            $response['data'] = $data;

        return response()->json($response, $code);
    }



    protected function getRequestQueryParameter(Request $request , $parameter , $params = [])
    {
        $defaultValue = array_key_exists('defaultValue' , $params) ? $params['defaultValue'] : NULL;
        $foundValue = array_key_exists('foundValue' , $params) ? $params['foundValue'] : NULL;

        if($request->query->has($parameter) || $request->query->get($parameter) == 0)
            return $foundValue == NULL ? $request->query->get($parameter) : $foundValue ;
        else
            return $defaultValue;
    }
}
