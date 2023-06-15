<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLoan\UserLoanHelper;
use App\Http\Controllers\ApiResponse;

class AdminControllerV1 extends Controller
{
    public function updateLoanStatus(Request $request){
        
        try{

            $loanStatus = $request->input('loan_status');
            $loanId = $request->input('loan_id');

            $loanObject = new UserLoanHelper();
            $loanObject->updateLoanStatus($loanStatus, $loanId);
            
            $data = ['message' => 'updated'];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            
            return ApiResponse::returnFailure($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    public function viewLoanList(){
        
        try{

            $loanObject = new UserLoanHelper();
            $loanList = $loanObject->viewLoanListForAdmin();
            
            $data = ['loanList' => $loanList];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            
            return ApiResponse::returnFailure($e->getMessage().' line'. $e->getLine());
        }
    }
    
}
