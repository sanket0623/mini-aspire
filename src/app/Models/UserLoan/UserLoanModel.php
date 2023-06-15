<?php

namespace App\Models\UserLoan;

use Illuminate\Database\Eloquent\Model;

class UserLoanModel extends Model
{
    protected $table = 'user_loan';
    protected $fillable = ['user_id', 'loan_amount', 'term'];
    protected $primaryKey = 'id';
    
    
    public function userPrepayment()
    {
         return $this->hasMany('App\Models\LoanPrePayment\LoanPrePaymentModel', 'loan_id', 'id');
    }
    
    
}