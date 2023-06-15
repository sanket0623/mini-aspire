<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require_once('restAPI/User/v1.php');
require_once('restAPI/Admin/v1.php');

Route::get('/', function () {
    echo 'Mini-aspire App';
});
