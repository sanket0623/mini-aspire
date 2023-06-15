<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLoan\UserLoanHelper;
use App\Http\Controllers\ApiResponse;
use App\Models\LoanPrePayment\LoanPrePaymentHelper;

class UserControllerV1 extends Controller
{
    
    public function CreateLoan(Request $request){
        
        try{

            $userId = $request->header('user_id');
            $loanAmount = $request->input('loan_amount');
            $term = $request->input('term');

            $loanObject = new UserLoanHelper();
            $loanId = $loanObject->createLoan($userId, $loanAmount, $term);
            
            $data = ['loan_id' => $loanId];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            
            return ApiResponse::returnFailure($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    
    public function viewLoanList(Request $request){
        
        try{

            $userId = $request->header('user_id');

            $loanObject = new UserLoanHelper();
            $loanList = $loanObject->viewLoanList($userId);
            
            $data = ['loanList' => $loanList];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            
            return ApiResponse::returnFailure($e->getMessage().' line'. $e->getLine());
        }
    }
    
    
    public function LoanPrepayment(Request $request){
        
        
         try{

            $installmentAmount = $request->input('amount_to_be_paid');
            $loanId = $request->input('loan_id');

            $prepaymentObject = new LoanPrePaymentHelper();
            $prepaymentObject->LoanPrepayment($loanId, $installmentAmount);
            
            $data = ['message' => 'updated'];
            return ApiResponse::returnData(['data' => $data]);
        } catch (\Throwable $e) {
            
            return ApiResponse::returnFailure($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    
}
