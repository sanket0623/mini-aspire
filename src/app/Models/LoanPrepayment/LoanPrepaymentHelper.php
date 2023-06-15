<?php

namespace App\Models\LoanPrePayment;

use App\Models\UserLoan\UserLoanModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\LoanPrePayment\LoanPrePaymentModel;
use App\Models\LoanPaymentHistory\LoanPaymentHistoryModel;
 

class LoanPrePaymentHelper {
    
    
    /**
     * Create New loan
     * @param type $userId
     * @param type $loanAmount
     * @param type $term
     * @throws \Exception
     */
    public function LoanPrepayment($loanId, $installmentAmount){
        
        try {
            /**
             * Start - Validate parameters
             */
            $requestParams = [
                'loanId' => $loanId,
                'installmentAmount' => $installmentAmount
            ];
            
            $rules = [
                'loanId' => 'required|integer',
                'installmentAmount' => 'required|decimal:2,4'
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
            
            /**
             * Check loan exist
             */
            if(!is_object($loanObject)){
                throw new \Exception('Loan does not exist');
            }
            
            /**
             * Laon is APPROVED state or not
             */
            if(in_array($loanObject->loan_status, ['PENDING', 'REJECTED', 'PAID'])){
                 throw new \Exception('Loan is '.$loanObject->loan_status.' status');
            }
            
            $loanPrepayment = LoanPrePaymentModel::where('loan_id', $loanId)->where('paid_status', "PENDING")->get();
            
            if(!is_object($loanPrepayment)){
                throw new \Exception('Pending prepayment does not exist');
            }
            
            /**
             * Check minimum amount to be paid
             */
            $minimunAmountToBePaid = ($loanPrepayment[0]->loan_amount - $loanPrepayment[0]->paid_amount);
            
            if($minimunAmountToBePaid > $installmentAmount){
                throw new \Exception('Minimum Amount can be paid '.$minimunAmountToBePaid);
            }
            
            /**
             * Deduction started
             */
            DB::beginTransaction();
            
            $remaining_amount = $installmentAmount;
            
            foreach ($loanPrepayment as $loan) {

                if ($remaining_amount >= ($loan->loan_amount - $loan->paid_amount)) {
                    $paid_status = 'PAID';
                    $amountPaid = $loan->loan_amount;
                    $amount_deducted = ($loan->loan_amount - $loan->paid_amount);
                } else {
                    $paid_status = 'PENDING';
                    $amountPaid = $remaining_amount;
                    $amount_deducted = $remaining_amount;
                }


                $loanObject = LoanPrePaymentModel::where('id', $loan->id)->first();
                $loanObject->paid_amount = $amountPaid;
                $loanObject->paid_status = $paid_status;
                $loanObject->paid_datetime = date('Y-m-d H:i:s');
                $loanObject->save();

                LoanPaymentHistoryModel::Create(
                        [
                            'loan_prepayment_id' => $loan->id,
                            'paid_amount' => $amount_deducted,
                            'paid_datetime' => date('Y-m-d H:i:s')
                        ]
                );

                $remaining_amount -= $amount_deducted;
                if ($remaining_amount <= 0) {
                    break;
                }
            }

            /**
             * Update loan status as PAID
             */
            $loanPendingCount = LoanPrePaymentModel::where('loan_id', $loanId)->where('paid_status', "PENDING")->count();
            
            if($loanPendingCount <= 0){
                
                $loanObject = UserLoanModel::where('id', $loanId)->first();
                $loanObject->loan_status = 'PAID';
                $loanObject->save();
                
                
            }
            
            DB::commit();
            


            return true;
            
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage().' line'. $e->getLine().' file'. $e->getFile());
        }
        
    }
    
}
