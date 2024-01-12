<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;
use App\Livewire\AssociateProduct;

Route::put('/user/profile-photo', [ProfilePhotoController::class, 'update'])->name('profile-photo.update');


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/search-stores', [MainController::class, 'searchStores'])->name('search-stores');


Route::get('/tienda/{nameStore}', [MainController::class, 'detailStore']);
Route::get('/tienda/{nameStore}/{linkProduct}', [MainController::class, 'detailStore']);
Route::get('/imgs-store', [AssociateProduct::class, 'store'])->name('imgs-store');
Route::get('/table-store-imgs', [AssociateProduct::class, 'storeData'])->name('table-store-imgs');





Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
