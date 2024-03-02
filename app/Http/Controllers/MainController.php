<?php

namespace App\Http\Controllers;

use App\Models\AditionalPicturesProduct;
use App\Models\Box;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\cylinderCapacity;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Promotion;
use App\Models\Publicity;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Table;
use App\Models\TypeStore;
use App\Models\Modell;
use App\Models\SubCategory;
use App\Models\TypeProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller{ 
    public function searchStores(){
        return view('search-stores');
    }

    public function detailStore(){
        return view('detail-store');
    }

    public function admin(){
        return view('admin.dashboard'); 
    }

    public function terminos(){
        return view('terminos');
    }
    public function preguntas(){
        return view('preguntas');
    }
    public function ayuda(){
        return view('ayuda');
    }
    public function politicas(){
        return view('politicas');
    }

    public function contacto(){
        return view('contacto');
    }

    public function publicity($id){

        $date = Carbon::now();

        $publicity = Publicity::find($id);
        $store = Store::find($publicity->stores_id);
        $publicities = Publicity::where('date_end', '>', $date)->where('status', true)->inRandomOrder()->limit(8)->get();

        $subscribed = false;

        if(isset(Auth::user()->id)){
            $subscribe = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $store->id)->first();
            if($subscribe != false){
                $subscribed = true;
            }

        }
        
        return view('publicity', ['publicity' => $publicity, 'publicities' => $publicities, 'subscribed' => $subscribed, 'store' => $store]);
    }

    public function subscribe(Request $request){
        if(!isset(Auth::user()->id)){
            return redirect('/login');
        }
        
        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $request->id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        //session()->flash('message', 'Suscrito exitosamente!!');
        return redirect('/publicities/'.str_replace(' ', '-', $request->id_p));
    }

    
    public function unsubscribe(Request $request){
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id',$request->id)->first();
        $subscribed->delete();
        //session()->flash('message', 'Suscripción anulada exitosamente!!');
        return redirect('/publicities/'.str_replace(' ', '-', $request->id_p));
    }

    public function register(){
        return view('register');
    }

    public function registerStore(){
        return view('register-store');
    }

    public function registerTaller(){
        return view('register-taller');
    }

    public function registerGrua(){
        return view('register-grua');
    }

    public function registerStorePost(Request $request){
    }

    public function registerDataStore(){
        $type_stores = TypeStore::all();
        $cities = City::all();
        $array_data = [
            'type_stores' => $type_stores,
            'cities' => $cities
        ];
        return view('register-data-store', $array_data);
    }


    public function registerDataTaller(){
        $type_stores = TypeStore::all();
        $cities = City::all();
        $array_data = [
            'type_stores' => $type_stores,
            'cities' => $cities
        ];
        return view('register-data-taller', $array_data);
    }

    public function registerDataGrua(){
        $type_stores = TypeStore::all();
        $cities = City::all();
        $array_data = [
            'type_stores' => $type_stores,
            'cities' => $cities
        ];
        return view('register-data-grua', $array_data);
    }


    public function productStoreMasive(){
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::where('status', true)->whereHas('typeStore', function ($query) {
            $query->where('description', env('TIPO_TIENDA'));
        })->get();
        $products = Product::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-store-massive', $data);
    }

    public function productDeleteMasive(){
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::all();
        $products = Product::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-delete-massive', $data);
    }

    public function productStoreDeleteMasive(){
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::all();
        $products = ProductStore::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-store-delete-massive', $data);
    }

    public function associteProducts(Request $request){
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);
        $amounts = explode(',', $request->amounts[0]);
        $prices = explode(',', $request->prices[0]);

        array_shift($products_ids);

        foreach($products_ids as $index => $key){
            if(count(ProductStore::where('products_id', $key)->where('stores_id', $request->store_id)->get()) > 0){
                $exist = true;
                continue; // Salta a la siguiente iteración
            }

            $product_store = new ProductStore();
            $product_store->products_id = $key;
            $product_store->stores_id = $request->store_id;
            $product_store->amount = $amounts[$index];
            $product_store->price = $prices[$index];
            $product_store->created_at = Carbon::now();

            $product_store->save();
        }

        if($exist){
            session()->flash('info', 'Algunos de los productos que ud selecciono ya estan asociados a esta tienda por lo que no fue necesario asociarlos');
        }

        session()->flash('message', 'Registros agregados exitosamente!!');
        return redirect('/admin/product_store_masive');
    }

    public function deleteProducts(Request $request){
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);

        array_shift($products_ids);

        foreach($products_ids as $key){
            if(count(ProductStore::where('products_id', $key)->get()) > 0){
                $exist = true;
                continue; // Salta a la siguiente iteración
            }

            AditionalPicturesProduct::where('products_id', $key)->delete();
            $pathDirectory = "public/images-prod/$key";
            Storage::deleteDirectory($pathDirectory);

            Product::destroy($key);
        }

        if($exist){
            session()->flash('info', 'Algunos de los productos que ud selecciono estan asociados a una tienda por lo que no se pueden eliminar');
        }

        session()->flash('message', 'Registros eliminados exitosamente!!');
        return redirect('/admin/product_delete_masive');
    }

    public function deleteProductStore(Request $request){
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);

        array_shift($products_ids);

        foreach($products_ids as $key){
            $product_id = ProductStore::find($key)->products_id;
            $promotions = Promotion::where('products_id', $product_id)->where('status', true)->get();
            if(count($promotions) > 0){
                $promotion = Promotion::where('products_id', $product_id)->where('status', true)->first();
                $date_end = Carbon::parse($promotion->date_end);
                $date = Carbon::now();
                if ($date->lte($date_end)) {
                    $exist = true;
                    continue; // Salta a la siguiente iteración
                }
            }

            ProductStore::destroy($key);
        }

        if($exist){
            session()->flash('info', 'Algunos de los productos que ud selecciono estan asociados a una promoción vigente por lo que no se pueden eliminar');
        }

        session()->flash('message', 'Registros eliminados exitosamente!!');
        return redirect('/admin/product_store_delete_masive');
    }

    public function products(){
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $products = Product::all();
        $categories = Category::all(); 
        $subCategories = SubCategory::all(); 
        $cylinder_capacities = cylinderCapacity::all();
        $models = Modell::all();
        $boxes = Box::all();
        $type_products = TypeProduct::all();
        $brands = Brand::all();
        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "products" => $products,
            "categories" => $categories,
            "categories" => $categories,
            "subCategories" => $subCategories,
            "cylinder_capacities" => $cylinder_capacities,
            "models" => $models,
            "boxes" => $boxes,
            "type_products" => $type_products,
            "brands" => $brands
        ];

        return view('products', $data);
    }

}
