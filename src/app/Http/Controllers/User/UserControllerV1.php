<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserControllerV1 extends Controller
{
    
    public function CreateLoan(Request $request){
        
        try{

            $userId = $request->header('user_id');
            $LoanAmount = $request->input('loan_amount');

            $bookingData = \App\Order\Models\BookingModel\BookingHelper::getTicketDataByParams($offerId, $orderId);

            $data = ['ticketData' => $bookingData];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            Logger::error(__METHOD__ . "  execption", [
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                'parameters' => $request->all()
            ]);
            return ApiResponse::returnFailureWithHeaderCode($e->getMessage(), HTTP_STATUS_CODE_OK, $e->getCode());
        }
        
    }
    
    
    public function ViewLoan(Request $request){
        
    }
    
    
    public function LoanPrepayment(Request $request){
        
    }
    
    
}
