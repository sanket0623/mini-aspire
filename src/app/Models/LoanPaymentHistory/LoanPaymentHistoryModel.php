<?php

namespace App\Models\LoanPaymentHistory;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentHistoryModel extends Model
{
    protected $table = 'loan_payment_history';
    protected $primaryKey = 'id';
    
    protected $fillable = ['loan_prepayment_id', 'paid_amount', 'paid_datetime'];
}