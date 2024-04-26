<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
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
Route::post('nullSubscription2', [MainController::class, 'nullSubscription2']);
Route::post('store_detail', [MainController::class, 'storeDetail']);
Route::post('products_store_detail', [MainController::class, 'ProductStoreDetail']);
Route::post('products_store_details', [MainController::class, 'ProductStoreDetails']);
Route::post('subscribe_store', [MainController::class, 'SubscribeStore']);
Route::post('publicity', [MainController::class, 'publicityDetail']);
Route::post('publicities', [MainController::class, 'pubilicitiesDetail']);
Route::post('updateData', [MainController::class, 'updateDataApi']);
Route::post('upload-image', [MainController::class, 'uploadImageApi']);
Route::get('countries', [MainController::class, 'getCountriesApi']);
Route::get('countries/{countryId}/states', [MainController::class, 'getStatesApi']);
Route::get('states/{stateId}/cities', [MainController::class, 'getCitiesByState']); // Obtener ciudades por estado
Route::get('product-detail/{productId}-{idStore}', [MainController::class, 'getProductDetail']);
Route::get('search-stores/{query}_{cityId}', [MainController::class, 'getStoreSearch']);
Route::get('search-products/{query}', [MainController::class, 'getProductsSearch']);
Route::get('search-products2/{query}-{id}', [MainController::class, 'getProductsSearch2']);
Route::get('/messages/{conversationId}-{userEmail}', [MessageController::class, 'index']);
Route::get('/chats/{userId}', [MainController::class, 'getChats']);
Route::post('/messages', [MessageController::class, 'store']);
