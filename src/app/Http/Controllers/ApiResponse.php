<?php
namespace App\Http\Controllers;

/* 
 * This class is for sending response
 * 
 */
class ApiResponse
{
    /**
     * Return failure json response
     * @param string $msg
     * @param int $error_code
     * @return string
     */
    public static function returnFailure(string $msg,int $error_code=0){
        $response_data=['status'=>false,'error'=>$msg,'error_code'=>$error_code];        
        return response()->json($response_data);
    }
    
    /**
     * Send sucess json response
     * @param array $data
     * @param array $extra_param
     * @return type
     */
    public static function returnData(array $data){
        
        $response_data=[
                        'internalStatusCode'=> 0,
                        'mainMessage'=> 'successful done',
                        'data' => $data['data']
                       ];
        return response()->json($response_data);
    }
} 