<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\user\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Identity verification/kyc
Route::group(['prefix' => 'others'], function () {
    Route::get('/banks', [BusinessController::class, 'getBanks'])->name('getBanks');
});

// Identity verification/kyc
Route::group(['prefix' => 'kyc'], function () {
    Route::post('/bvn/information', [UserController::class, 'verifyInformation'])->name('verifyInformation');
    Route::post('/bvn/account', [UserController::class, 'verifyAccount'])->name('verifyAccount');
});