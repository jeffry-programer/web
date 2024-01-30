<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;
use App\Livewire\AssociateProduct;
use App\Livewire\UserManagement;

Route::put('/user/profile-photo', [ProfilePhotoController::class, 'update'])->name('profile-photo.update');


Route::get('/', function () {
    return view('dashboard');
});



Route::get('/terminos', [MainController::class, 'terminos'])->name('terminos');
Route::get('/preguntas', [MainController::class, 'preguntas'])->name('preguntas');
Route::get('/admin/table-management/{label}', UserManagement::class)->name('admin/table-management/{label}');


Route::get('/search-stores', [MainController::class, 'searchStores'])->name('search-stores');
Route::get('/tienda/{nameStore}', [MainController::class, 'detailStore']);
Route::get('/tienda/{nameStore}/{linkProduct}', [MainController::class, 'detailStore']);
Route::get('/imgs-store', [AssociateProduct::class, 'store'])->name('imgs-store');
Route::get('/table-store-imgs', [AssociateProduct::class, 'storeData'])->name('table-store-imgs');

//Admin
Route::middleware('auth')->group(function () {
    Route::get('/table-management/{label}', UserManagement::class)->name('/table-management/{label}');
    Route::post('table-store', [UserManagement::class, 'store'])->name('table-store');
    Route::post('table-update', [UserManagement::class, 'update'])->name('table-update');
    Route::post('delete-register', [UserManagement::class, 'delete'])->name('delete-register');
    Route::post('imgs-store', [UserManagement::class, 'saveImgs'])->name('imgs-store');
    Route::post('table-store-imgs', [UserManagement::class, 'store2'])->name('table-store-imgs');
    Route::post('update-store-imgs', [UserManagement::class, 'update2'])->name('update-store-imgs');
    Route::post('imgs-update', [UserManagement::class, 'saveImgs'])->name('imgs-update');
    Route::post('delete-img', [UserManagement::class, 'deleteImg'])->name('delete-img');
    Route::post('search-data', [UserManagement::class, 'searchData'])->name('search-data');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
