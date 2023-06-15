<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$restApiVersion = 'admin/v1';

Route::post($restApiVersion.'/updateLoanStatus', [App\Http\Controllers\Admin\AdminControllerV1::class,'updateLoanStatus']);
Route::get($restApiVersion.'/viewLoan', [App\Http\Controllers\Admin\AdminControllerV1::class,'ViewLoan']);



