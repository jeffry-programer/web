<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Publicity;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DetailStore extends Component
{
    use WithPagination;

    public $product_detail = null;
    public $brand = null;
    public $product_store = null;
    public $numbers_store = [];
    public $paginate = 6;
    public $showMessageNotFoundProducts = false;
    public $global_store = [];
    public $subscribed = false;
    public $subscriptions = [];

    public $search_products = false;

    public function render(){
        if(str_contains($_SERVER['REQUEST_URI'], 'update')){
            $this->search_products = true;
        }

        if(str_contains($_SERVER['REQUEST_URI'], '?')){
            $array = explode('&', explode('?', $_SERVER['REQUEST_URI'])[1]);
            $category = explode('=', $array[0])[1];
            $product = explode('=', $array[1])[1];
        }
        $name_store = str_replace('-',' ', explode('?', explode('/', $_SERVER['REQUEST_URI'])[2]))[0];

        // Array asociativo con las vocales acentuadas y sus codificaciones URL
        $vocales = array(
            '%C3%A1' => 'á',
            '%C3%a1' => 'á',
            '%C3%89' => 'É',
            '%C3%89' => 'É',
            '%C3%AD' => 'í',
            '%C3%8D' => 'Í',
            '%C3%B3' => 'ó',
            '%C3%93' => 'Ó',
            '%C3%BA' => 'ú',
            '%C3%9A' => 'Ú',
            '%C3%A1' => 'Á',
            '%C3%81' => 'Á',
            '%C3%89' => 'É',
            '%C3%89' => 'É',
            '%C3%8D' => 'Í',
            '%C3%8D' => 'Í',
            '%C3%93' => 'Ó',
            '%C3%93' => 'Ó',
            '%C3%9A' => 'Ú',
            '%C3%9A' => 'Ú',
        );

        // Reemplazar cada vocal acentuada por su equivalente sin codificación URL
        $name_store = str_replace(array_keys($vocales), array_values($vocales), $name_store);

        if($name_store == 'update'){
            $name_store = str_replace('-',' ', explode('?', explode('/', redirect()->getUrlGenerator()->previous())[4]))[0];
        }
        $categories = Category::all();
        $store = Store::where('name', $name_store)->first();
        if(isset(Auth::user()->id)){
            $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $store->id)->get();
            if(count($subscribed) != 0){
                $this->subscribed = true;
            }
        }

        $this->subscriptions = Subscription::where('stores_id', $store->id)->get();

        
        $this->global_store = $store;
        $link_product = "";
        if(isset(explode('/', $_SERVER['REQUEST_URI'])[3])){
            $link_product = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];
            $this->product_detail = Product::where('link', $link_product)->first();
            $this->brand = Brand::find($this->product_detail->brands_id)->first()->description;
            $this->product_store = ProductStore::where('stores_id', $store->id)->where('products_id', $this->product_detail->id)->first();
        }

        if(str_contains($_SERVER['REQUEST_URI'], '?page')){
            $this->product_detail = null;
        }

        $number1 = User::find($store->users_id)->phone;
        $number2 = $store->phone;

        $this->numbers_store = [$number1, $number2];

        if(isset($product)){
            if($category != 'Categoria'){
                $id_sub_category = SubCategory::where('categories_id', $category)->select('id')->first()->id;
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->whereFullText('products.name', $product)->where('products.sub_categories_id', $id_sub_category)->paginate($this->paginate);
            }else{
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->whereFullText('products.name', $product)->paginate($this->paginate);
            }

            if(count($products) == 0){
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->paginate($this->paginate);
                $this->showMessageNotFoundProducts = true;
            }

            $this->search_products = true;

        }else{
            $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->paginate($this->paginate);
        }

        $publicities = Publicity::where('date_end', '>', Carbon::now())->where('status', true)->inRandomOrder()->limit(8)->get();


        $array_data = [
            'store' => $store, 
            'categories' => $categories,
            'products' => $products,
            'publicities' => $publicities
        ];
        
        return view('livewire.detail-store', $array_data);
    }

    public function getRandomAds()
    {
        $ads = Publicity::where('date_end', '>', Carbon::now())->where('status', true)->inRandomOrder()->limit(8)->get();
        return response()->json($ads);
    }

    public function subscribe(){
        if(!isset(Auth::user()->id)){
            return redirect('/login');
        }
        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $this->global_store['id'];
        $subscription->created_at = Carbon::now();
        $subscription->save();

        //session()->flash('message', 'Suscrito exitosamente!!');
        return redirect('/tienda/'.str_replace(' ', '-', $this->global_store['name']));
    }

    public function nullSubscribe(){
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $this->global_store['id'])->first();
        $subscribed->delete();
        //session()->flash('message', 'Suscripción anulada exitosamente!!');
        return redirect('/tienda/'.str_replace(' ', '-', $this->global_store['name']));
    }
}