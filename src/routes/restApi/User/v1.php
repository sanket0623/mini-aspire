<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$restApiVersion = 'user/v1';


Route::post($restApiVersion.'/createLoan', [App\Http\Controllers\User\UserControllerV1::class,'CreateLoan']);
Route::get($restApiVersion.'/viewLoanList', [App\Http\Controllers\User\UserControllerV1::class,'viewLoanList']);
Route::post($restApiVersion.'/loanPrepayment', [App\Http\Controllers\User\UserControllerV1::class,'LoanPrepayment']);



