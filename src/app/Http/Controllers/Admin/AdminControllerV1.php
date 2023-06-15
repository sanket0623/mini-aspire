<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLoan\UserLoanHelper;
use App\Http\Controllers\ApiResponse;

class AdminControllerV1 extends Controller
{
    public function updateLoanStatus(Request $request){
        
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
