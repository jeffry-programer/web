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

Route::get('/get-stores-promotion', [MainController::class, 'getAllStoresPromotion']);
Route::get('/get-info-home/{userId}-{municipalityId}', [MainController::class, 'getInfoHome']);
Route::post('register',  [MainController::class, 'registerApi']);
Route::post('/login-or-register-google', [MainController::class, 'loginOrRegisterWithGoogle']);
Route::post('login', [MainController::class, 'loginApi']);
Route::post('logout', [MainController::class, 'logoutApi']);
Route::post('verifiedApi', [MainController::class, 'verifiedApi']);
Route::post('subscriptions', [MainController::class, 'subscriptionsApi']);
Route::post('my-publicities', [MainController::class, 'myPublicitiesApi']);
Route::post('my-promotions', [MainController::class, 'myPromotionsApi']);
Route::post('sendVerifiedEmailApi', [MainController::class, 'sendVerifiedEmailApi']);
Route::post('nullSubscription', [MainController::class, 'nullSubscription']);
Route::post('nullSubscription2', [MainController::class, 'nullSubscription2']);
Route::post('store_detail', [MainController::class, 'storeDetail']);
Route::post('/renew-plan', [MainController::class, 'renewPlan']);
Route::post('products_store_detail', [MainController::class, 'ProductStoreDetail']);
Route::post('products_store_details', [MainController::class, 'ProductStoreDetails']);
Route::post('subscribe_store', [MainController::class, 'SubscribeStore']);
Route::post('publicity', [MainController::class, 'publicityDetail']);
Route::post('publicities', [MainController::class, 'pubilicitiesDetail']);
Route::post('updateData', [MainController::class, 'updateDataApi']);
Route::post('updateDataStore', [MainController::class, 'updateDataStoreApi']);
Route::post('upload-image', [MainController::class, 'uploadImageApi']);
Route::post('upload-image-store', [MainController::class, 'uploadImageApi2']);
Route::get('countries', [MainController::class, 'getCountriesApi']);
Route::get('countries/{countryId}/states', [MainController::class, 'getStatesApi']);
Route::get('states/{stateId}/municipalities', [MainController::class, 'getMunicipalityByState']); // Obtener ciudades por estado
Route::get('municipalities/{cityId}/sectors', [MainController::class, 'getSectorsByMunicipality']); // Obtener sectores por ciudad
Route::get('product-detail/{productId}_{idStore}_{idUser}', [MainController::class, 'getProductDetail']);
Route::get('search-stores', [MainController::class, 'getStoreSearch']);
Route::get('search-stores2', [MainController::class, 'getStoreSearch2']);
Route::get('search-products/{query}', [MainController::class, 'getProductsSearch']);
Route::get('search-products2/{query}-{id}', [MainController::class, 'getProductsSearch2']);
Route::get('search-products3/{query}', [MainController::class, 'getProductsSearch3']);
Route::get('/messages/{conversationId}-{userEmail}', [MessageController::class, 'index']);
Route::get('/chats/{userId}', [MainController::class, 'getChats']);
Route::post('/messages', [MessageController::class, 'store']);
Route::post('/registerStore', [MainController::class, 'registerStorePost']);
Route::get('/type-publicities/{userId}', [MainController::class, 'typePublicities']);
Route::post('/save-publicity', [MainController::class, 'savePublicity']);
Route::post('/save-promotion', [MainController::class, 'savePromotion']);
Route::get('/municipalities', [MainController::class, 'getMunicipalities']);
Route::get('/states', [MainController::class, 'getStates']);
Route::post('/send-signal-aux', [MainController::class, 'sendSignalAux']);
Route::get('/get-signals-aux', [MainController::class, 'getSignalsAux']);
Route::post('/change-status-signal', [MainController::class, 'changeStatusSignalsAux']);
Route::post('/remove-status-signal', [MainController::class, 'removeSignalAux']);
Route::post('/remove-signals', [MainController::class, 'removeSignalsAux']);
Route::post('/close-status-signal', [MainController::class, 'closeSignalsAux']);
Route::post('/quality-signal', [MainController::class, 'qualitySignal']);
Route::post('/change-status-message', [MessageController::class, 'changeStatusMessage']);
Route::post('/reset-password', [MainController::class, 'resetPassword']);
Route::post('/change-password', [MainController::class, 'changePassword']);
Route::post('/save-product', [MainController::class, 'saveProduct']);
Route::post('/store-products', [MainController::class, 'store']);





