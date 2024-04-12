<?php

use App\Http\Controllers\MainController;
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

Route::get('/publicities', [MainController::class, 'getPublicitiesApi']);
Route::get('/stores', [MainController::class, 'getStoresApi']);
Route::post('register',  [MainController::class, 'registerApi']);
Route::post('login', [MainController::class, 'loginApi']);
Route::post('verifiedApi', [MainController::class, 'verifiedApi']);
Route::post('subscriptions', [MainController::class, 'subscriptionsApi']);
Route::post('sendVerifiedEmailApi', [MainController::class, 'sendVerifiedEmailApi']);
Route::post('nullSubscription', [MainController::class, 'nullSubscription']);

