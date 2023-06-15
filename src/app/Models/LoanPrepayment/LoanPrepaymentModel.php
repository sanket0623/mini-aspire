<?php

namespace App\Models\LoanPrePayment;

use Illuminate\Database\Eloquent\Model;

class LoanPrePaymentModel extends Model
{
    protected $table = 'loan_prepayment';
    protected $primaryKey = 'id';
    protected $fillable = ['loan_id', 'loan_amount', 'term_date'];
    
}