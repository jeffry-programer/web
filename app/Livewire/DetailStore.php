<?php

namespace App\Livewire;

use App\Http\Controllers\MainController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ExchangeRate;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Promotion;
use App\Models\Publicity;
use App\Models\Renovation;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
            $this->product_detail = Product::with('promotions')->where('name', str_replace('-',' ',$link_product))->first();
            $this->brand = Brand::find($this->product_detail->brands_id)->first()->description;
            $this->product_store = ProductStore::where('stores_id', $store->id)->where('products_id', $this->product_detail->id)->first();
            if($this->product_store == null) return redirect('/tienda/'.str_replace(' ','-', $store->name));
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
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->where('products.name', 'like', '%'.$product.'%')->where('products.sub_categories_id', $id_sub_category)->paginate($this->paginate);
            }else{
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->where('products.name', 'like', '%'.$product.'%')->paginate($this->paginate);
            }

            if(count($products) == 0){
                $products = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->paginate($this->paginate);
                $products_total = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->count();
                $this->showMessageNotFoundProducts = true;
            }else{
                if($category != 'Categoria'){
                    $id_sub_category = SubCategory::where('categories_id', $category)->select('id')->first()->id;
                    $products_total = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->where('products.name', 'like', '%'.$product.'%')->where('products.sub_categories_id', $id_sub_category)->count();
                }else{
                    $products_total = ProductStore::join('products','product_stores.products_id','=','products.id')->where('stores_id', $store->id)->where('products.name', 'like', '%'.$product.'%')->count();
                }
            }

            $this->search_products = true;
        }else{
            $products = Store::find($this->global_store['id'])->products()->paginate($this->paginate);
            $products_total = Store::find($this->global_store['id'])->products()->count();
        }

        $products_promotion = $store->products()->whereHas('promotions', function ($query) {
            $today = Carbon::now()->toDateString();
            $query->whereDate('date_init', '<=', $today)
                  ->whereDate('date_end', '>=', $today)
                  ->where('status', true);
        })->with('promotions')->get();

        $publicities = Publicity::where('date_end', '>', Carbon::now())->where('status', true)->inRandomOrder()->limit(8)->get();

        if(isset($_GET['product'])){
            $search = $_GET['product'];
        }else{
            $search = '';
        }

        if(isset($_GET['tBGZall1t5CCeUqrQOkM'])){
            $category = $_GET['tBGZall1t5CCeUqrQOkM'];
        }else{
            $category = '';
        }

        $today = Carbon::now()->toDateString();

        if(isset($this->product_detail->id)){
            $promotion = Promotion::where('stores_id', $this->global_store->id)->where('products_id', $this->product_detail->id)->whereDate('date_init', '<=', $today)->whereDate('date_end', '>=', $today)->where('status', true)->first();
        }else{
            $promotion = null;
        }

        if($promotion != null){
            $this->product_detail->promotion = $promotion;
        }

        $rate = $this->getExchangeRate();
        $renovation = Renovation::where('stores_id', $store->id)->where('status', false)->exists();

        $array_data = [
            'store' => $store, 
            'categories' => $categories,
            'products' => $products,
            'publicities' => $publicities,
            'products_total' => $products_total,
            'products_promotion' => $products_promotion,
            'search' => $search,
            'category' => $category,
            'plans' => Plan::all(),
            'rate' => $rate,
            'renovation' => $renovation
        ];

        $store->address = Crypt::decrypt($store->address);
        $store->email = Crypt::decrypt($store->email);
        $store->RIF = Crypt::decrypt($store->RIF);
        $store->phone = Crypt::decrypt($store->phone);
        
        return view('livewire.detail-store', $array_data);
    }

    public function getMoreProducts(){
        $products = Store::find($this->global_store['id'])->products()->paginate($this->paginate);
        return response()->json($products);
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

    private function getExchangeRate()
    {
        // Intenta obtener el último tipo de cambio
        $exchangeRate = ExchangeRate::latest()->first();

        // Si no hay registro o el último cambio es más viejo que 24 horas
        if (!$exchangeRate || $exchangeRate->updated_at < now()->subDay()) {
            // Obtener la tasa de cambio de la API
            $response = file_get_contents('https://api.exchangerate-api.com/v4/latest/USD');
            $data = json_decode($response, true);

            // Almacenar la nueva tasa de cambio
            $exchangeRate = new ExchangeRate();
            $exchangeRate->rate = $data['rates']['VES'];
            $exchangeRate->updated_at = now();
            $exchangeRate->save();
        }

        return $exchangeRate->rate;
    }
}