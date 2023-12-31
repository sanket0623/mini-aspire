<?php

namespace App\Models\UserLoan;

use App\Models\UserLoan\UserLoanModel;
use App\Models\LoanPrePayment\LoanPrePaymentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
 

class UserLoanHelper {
    
    
    /**
     * Create New loan
     * @param type $userId
     * @param type $loanAmount
     * @param type $term
     * @throws \Exception
     */
    public function createLoan($userId, $loanAmount, $term){
        
        try {
            /**
             * Start - Validate parameters
             */
            $requestParams = [
                'userId' => $userId,
                'loanAmount' => $loanAmount
            ];
            
            $rules = [
                'userId' => 'required|integer',
                'loanAmount' => 'required|decimal:2,4'
            ];

            $validator = Validator::make($requestParams, $rules);


            if ($validator->fails()) {
                $errors = $validator->messages();
                throw new \Exception($errors->all()[0]);
            }
            
            /**
             * end - validate parameters
             */
            
            DB::beginTransaction();
            
            $loan = UserLoanModel::Create(
                    ['user_id' => $userId,
                        'loan_amount' => $loanAmount,
                        'term' => $term]);
            
            $installment = round(($loanAmount/$term),4);
            $totalInstallmentRaised =0;
            
           
            for($i=1;$i<= $term;$i++){
                
                /**
                 * Get remaining record for final term
                 */
                if($i == $term){
                    $installment = $loanAmount - $totalInstallmentRaised;
                }
                
                $days = 7 * $i;
                $perpaymentInstallment = [
                    'loan_id' => $loan->id,
                    'loan_amount' => $installment,
                    'term_date' => date('Y-m-d', strtotime("+ $days days"))
                ];
                
                $totalInstallmentRaised += $installment;
                
                LoanPrePaymentModel::Create($perpaymentInstallment);
            }
            
            DB::commit();
            
            return $loan->id;
            
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    
    /**
     * View loan
     * @param type $userId
     * @param type $loanAmount
     * @param type $term
     * @return type
     * @throws \Exception
     */
    public function viewLoanList($userId){
        
        try {
            /**
             * Start - Validate parameters
             */
            $requestParams = [
                'userId' => $userId
            ];
            
            $rules = [
                'userId' => 'required|integer'
            ];

            $validator = Validator::make($requestParams, $rules);


            if ($validator->fails()) {
                $errors = $validator->messages();
                throw new \Exception($errors->all()[0]);
            }
            
            /**
             * end - validate parameters
             */
            
            $loanList = UserLoanModel::where('user_id', $userId)->orderBy('id', 'desc')->paginate(5);
            
            $loanData = [];
            
            foreach($loanList as $list){
                $loanData[] = [
                    'loan_id' => $list->id,
                    'loan_amount' => $list->loan_amount,
                    'term' => $list->term,
                    'loan_status' => $list->loan_status,
                    'created_at' => $list->created_at,
                    'updated_at' => $list->updated_at,
                    'installment' => $list->userPrepayment->toArray()
                 ];
            }
             return $loanData;
            
        } catch (\Throwable $e) {
            
            throw new \Exception($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    /**
     * View loan list for admin
     * @return type
     * @throws \Exception
     */
    public function viewLoanListForAdmin(){
        
        try {
            
            $loanList = UserLoanModel::orderBy('id', 'desc')->paginate(5);
            
            $loanData = [];
            
            foreach($loanList as $list){
                $loanData[] = [
                    'loan_id' => $list->id,
                    'user' => ['name' => $list->user->name, 'email' => $list->user->email],
                    'loan_amount' => $list->loan_amount,
                    'term' => $list->term,
                    'loan_status' => $list->loan_status,
                    'created_at' => $list->created_at,
                    'updated_at' => $list->updated_at,
                    'installment' => $list->userPrepayment->toArray()
                 ];
            }
             return $loanData;
            
        } catch (\Throwable $e) {
            
            throw new \Exception($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
    
   
     /**
      * Update loan status
      * @param type $loanStatus
      * @param type $loanId
      * @return type
      * @throws \Exception
      */
    public function updateLoanStatus($loanStatus, $loanId){
        
        try {
            /**
             * Start - Validate parameters
             */
            $requestParams = [
                'loanStatus' => $loanStatus,
                'loanId' => $loanId
            ];
            
            $rules = [
                'loanId' => 'required|integer',
                'loanStatus' => ['required', Rule::in(['APPROVED', "REJECTED"])]
            ];

            $validator = Validator::make($requestParams, $rules);


            if ($validator->fails()) {
                $errors = $validator->messages();
                throw new \Exception($errors->all()[0]);
            }
            
            /**
             * end - validate parameters
             */
            
            
            $loanObject = UserLoanModel::where('id', $loanId)->first();
            
            if(!is_object($loanObject)){
                throw new \Exception("Loan id does not exist");
            }
            
            if($loanObject->loan_status  != "PENDING"){
                throw new \Exception("Action already taken");
            }
            
            
            $loanObject->loan_status = $loanStatus;
            $loanObject->save();
            
            return true;
            
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage().' line'. $e->getLine());
        }
        
    }
    
}
