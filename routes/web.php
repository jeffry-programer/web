<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;
use App\Livewire\AssociateProduct;
use App\Livewire\DetailStore;
use App\Livewire\Subscribe;
use App\Livewire\UserManagement;
use Illuminate\Support\Facades\Storage;

Route::put('/user/profile-photo', [ProfilePhotoController::class, 'update'])->name('profile-photo.update');


Route::get('/', function () {
    return view('dashboard');
});

Route::post('login2', [AuthController::class, 'loginUser'])->name('login2');
Route::post('register2', [AuthController::class, 'registerUser'])->name('register2');
Route::post('forgetpassowrd2', [AuthController::class, 'forgetPassword'])->name('forgetpassowrd2');
Route::post('password-update', [AuthController::class, 'resetPassword'])->name('password-update');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');


Route::get('/register-grua', [MainController::class, 'registerGrua'])->name('register-grua');
Route::get('/register-taller', [MainController::class, 'registerTaller'])->name('register-taller');
Route::get('/register-store', [MainController::class, 'registerStore'])->name('register-store');
Route::get('/register-cauchera', [MainController::class, 'registerCauchera'])->name('register-cauchera');
Route::get('/register-otros', [MainController::class, 'registerOtros'])->name('register-otros');
Route::post('/register-store', [MainController::class, 'registerStorePost'])->name('register-store');


Route::get('/registro', [MainController::class, 'register'])->name('registro');
Route::get('/terminos', [MainController::class, 'terminos'])->name('terminos');
Route::get('/preguntas', [MainController::class, 'preguntas'])->name('preguntas');
Route::get('/ayuda', [MainController::class, 'ayuda'])->name('ayuda');
Route::get('/politicas', [MainController::class, 'politicas'])->name('politicas');
Route::get('/contacto', [MainController::class, 'contacto'])->name('contacto');

Route::get('/search-stores', [MainController::class, 'searchStores'])->name('search-stores');
Route::get('/imgs-store', [AssociateProduct::class, 'store'])->name('imgs-store');
Route::get('/table-store-imgs', [AssociateProduct::class, 'storeData'])->name('table-store-imgs');
Route::get('/publicities/{id}', [MainController::class, 'publicity']);
Route::get('/autocomplete-products', [MainController::class, 'autocompleteProducts'])->name('autocomplete-products');
Route::get('/autocomplete-products-store', [MainController::class, 'autocompleteProductStore'])->name('autocomplete-products-store');
Route::get('update-counter-component', [MainController::class, 'updateComponent']);
Route::get('/states/{state}/municipalities', [MainController::class, 'municipalities']);
Route::post('/stores', [MainController::class, 'getMoreStores'])->name('stores');
Route::get('/get-categories', [MainController::class, 'getCategoriesStore']);

// Ruta para obtener los municipios basado en el estado seleccionado
Route::post('/get-municipalities', [MainController::class, 'getMunicipalities2'])->name('get.municipalities');

// Ruta para obtener los sectores basado en el municipio seleccionado
Route::post('/get-sectors', [MainController::class, 'getSectors'])->name('get.sectors');

Route::get('/create-sucursal', [MainController::class, 'createSucursal'])->name('create-sucursal');;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/register-data-grua', [MainController::class, 'registerDataGrua'])->name('register-data-grua');
    Route::get('/register-data-store', [MainController::class, 'registerDataStore'])->name('register-data-store');
    Route::get('/register-data-taller', [MainController::class, 'registerDataTaller'])->name('register-data-taller');
    Route::get('/register-data-cauchera', [MainController::class, 'registerDataCauchera'])->name('register-data-cauchera');
    Route::get('/register-data-otros', [MainController::class, 'registerDataOtros'])->name('register-data-otros');
    Route::post('table-store', [UserManagement::class, 'store'])->name('table-store');
    Route::post('table-update', [UserManagement::class, 'update'])->name('table-update');
    Route::post('delete-register', [UserManagement::class, 'delete'])->name('delete-register');
    Route::post('imgs-store', [UserManagement::class, 'saveImgs'])->name('imgs-store');
    Route::post('imgs-store-data', [UserManagement::class, 'saveImgs2'])->name('imgs-store-data');
    Route::post('table-store-imgs-5', [UserManagement::class, 'registerPublicity'])->name('table-store-imgs-5');
    Route::post('table-store-imgs-4', [UserManagement::class, 'registerPromotion'])->name('table-store-imgs-4');
    Route::post('table-store-imgs-3', [UserManagement::class, 'registerProductStore'])->name('table-store-imgs-3');
    Route::post('table-store-imgs-2', [UserManagement::class, 'registerStore'])->name('table-store-imgs-2');
    Route::post('table-store-imgs-6', [UserManagement::class, 'registerSucursal'])->name('table-store-imgs-6');
    Route::post('table-store-imgs', [UserManagement::class, 'store2'])->name('table-store-imgs');
    Route::post('update-store-imgs', [UserManagement::class, 'update2'])->name('update-store-imgs');
    Route::post('update-store-imgs-2', [UserManagement::class, 'updateStore'])->name('update-store-imgs-2');
    Route::post('imgs-update', [UserManagement::class, 'saveImgs'])->name('imgs-update');
    Route::post('delete-img', [UserManagement::class, 'deleteImg'])->name('delete-img');
    Route::post('search-data', [UserManagement::class, 'searchData'])->name('search-data');
    Route::post('/subscribe', [MainController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [MainController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/obtener-subcategorias', [UserManagement::class, 'changeCategory'])->name('obtener-sucategorias');
    Route::get('/tienda/{nameStore}', [MainController::class, 'detailStore']);
    Route::get('/tienda/{nameStore}/{linkProduct}', [MainController::class, 'detailStore']);
    Route::post('/asociate-products', [MainController::class, 'associteProducts'])->name('asociate-products');
    Route::post('/delete-products', [MainController::class, 'deleteProducts'])->name('delete-products');
    Route::post('/delete-products-store', [MainController::class, 'deleteProductStore'])->name('delete-products-store');
    Route::get('/get-random-ads', [DetailStore::class, 'getRandomAds'])->name('get-random-ads');
    Route::post('/products', [MainController::class, 'getMoreProducts'])->name('products');
    Route::post('/upload-image-store', [MainController::class, 'uploadImageStore'])->name('upload-image-store');
    Route::post('/subscribe-user', [Subscribe::class, 'subscribe'])->name('subscribe-user');
    Route::post('/null-subscribe', [Subscribe::class, 'nullSubscribe'])->name('null-subscribe');
    Route::get('/municipalities/{municipality}/sectors', [MainController::class, 'sectors']);
    Route::get('/your-data-route', [MainController::class, 'getData'])->name('your.data.route');
    Route::get('/products.data', [MainController::class, 'getData2'])->name('products.data');
    Route::post('/renovation.store', [MainController::class, 'renovationStore'])->name('renovation.store');
});

Route::middleware('auth.admin',config('jetstream.auth_session'),'verified')->group(function () {
    Route::get('/admin/product_store_masive', [MainController::class, 'productStoreMasive']);
    Route::get('/admin/product_delete_masive', [MainController::class, 'productDeleteMasive']);
    Route::get('/admin/products', [MainController::class, 'products']);
    Route::get('/admin/product_store_delete_masive', [MainController::class, 'productStoreDeleteMasive']);
    Route::get('/admin/table-management/{label}', UserManagement::class)->name('admin/table-management/{label}');
    Route::get('/table-management/{label}', UserManagement::class)->name('/table-management/{label}');
    Route::post('/aprove-renovation', [UserManagement::class, 'aproveRenovation'])->name('aprove-renovation');
    Route::post('/decline-renovation', [UserManagement::class, 'declineRenovation'])->name('decline-renovation');
    Route::post('/datatable-products', [MainController::class, 'getProducts'])->name('datatable.products');
});

Route::get('/download-receipt/{id}', function ($id) {
    $renovation = App\Models\Renovation::findOrFail($id);

    // Obtén la ruta del comprobante
    $filePath = $renovation->receipt_path;

    // Verifica si el archivo existe
    if (Storage::exists($filePath)) {
        return Storage::download($filePath);
    } else {
        return back()->with('error', 'El comprobante no existe.');
    }
})->name('download.receipt');

//Route::get('/test', [MainController::class, 'test'])->name('/test');

