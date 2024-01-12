<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\State;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStores extends Component
{
    use WithPagination;

    public $paginate = 10;
    
    public function render(){
        if(!isset(explode('?', $_SERVER['REQUEST_URI'])[1])){
            $array_data = explode('&', redirect()->getUrlGenerator()->previous());
        }else{
            $array_data = explode('&', explode('?', $_SERVER['REQUEST_URI'])[1]);
        }
        
        $country_id = explode('=', $array_data[0])[1];
        $state_id =  explode('=', $array_data[1])[1];
        $cities_id = explode('=', $array_data[2])[1];
        $categories_id = explode('=', $array_data[3])[1];
        $product_search = explode('=', $array_data[4])[1];

        $search_found = "";

        if($cities_id != ''){
            $stores = $this->queryDataCity($categories_id, $product_search, $cities_id);
            $search_found = "cities";
            if(count($stores) == 0){
                $stores = $this->queryDataState($categories_id, $product_search, $state_id);
                $search_found = "estado";
                if(count($stores) == 0){
                    $stores = $this->queryDataCountry($categories_id, $product_search, $country_id);
                    $search_found = "país";
                }
            }
        }else{
            $stores = $this->queryData($categories_id, $product_search);
        }

        if($search_found != 'cities' && $search_found != ''){
            if($search_found == 'estado'){
                $search_found = $search_found . ' ' . State::find($state_id)->name;
            }else{
                $search_found = $search_found . ' ' . Country::find($country_id)->description;
            }
        }
        
        if(count($stores) == 0){
            $empty_stores = true;
        }else{
            $empty_stores = false;
        }
        
        return view('livewire.search-stores', [
            'stores' => $stores,
            'empty_stores' => $empty_stores,
            'product_search' => $product_search,
            'search_found' => $search_found
        ]);
    }

    public function queryData($categories_id, $product_search){
        $query = Store::join('product_stores', 'stores.id' , '=' , 'product_stores.stores_id')->join('products', 'products.id' , '=' , 'product_stores.products_id');
        $query = $query->whereFullText('products.name', $product_search)->where('product_stores.amount', '>', 0);

        if($categories_id != 'Categoria'){
            $id_sub_category = SubCategory::where('categories_id', $categories_id)->select('id')->first()->id;
            $query = $query->where('products.sub_categories_id', $id_sub_category);
        }
        return $query->select('stores.name','stores.address','stores.image','stores.description','products.link')->paginate($this->paginate);
    }

    public function queryDataCity($categories_id, $product_search, $city_id){        
        $query = Store::join('product_stores', 'stores.id' , '=' , 'product_stores.stores_id')->join('products', 'products.id' , '=' , 'product_stores.products_id');
        $query = $query->whereFullText('products.name', $product_search)->where('product_stores.amount', '>', 0)->where('cities_id', $city_id);

        if($categories_id != 'Categoria'){
            $id_sub_category = SubCategory::where('categories_id', $categories_id)->select('id')->first()->id;
            $query = $query->where('products.sub_categories_id', $id_sub_category);
        }
        return $query->select('stores.name','stores.address','stores.image','stores.description','products.link')->paginate($this->paginate);
    }

    public function queryDataState($categories_id, $product_search, $state_id){
        $query = Store::join('product_stores', 'stores.id' , '=' , 'product_stores.stores_id')->join('products', 'products.id' , '=' , 'product_stores.products_id')->join('cities', 'stores.cities_id' , '=' , 'cities.id')->join('municipalities', 'cities.municipalities_id' , '=' , 'municipalities.id');
        $query = $query->whereFullText('products.name', $product_search)->where('product_stores.amount', '>', 0)->where('municipalities.states_id', $state_id);

        if($categories_id != 'Categoria'){
            $id_sub_category = SubCategory::where('categories_id', $categories_id)->select('id')->first()->id;
            $query = $query->where('products.sub_categories_id', $id_sub_category);
        }
        return $query->select('stores.name','stores.address','stores.image','stores.description','products.link')->paginate($this->paginate);
    }

    public function queryDataCountry($categories_id, $product_search, $country_id){
        $query = Store::join('product_stores', 'stores.id' , '=' , 'product_stores.stores_id')->join('products', 'products.id' , '=' , 'product_stores.products_id')->join('cities', 'stores.cities_id' , '=' , 'cities.id')->join('municipalities', 'cities.municipalities_id' , '=' , 'municipalities.id')->join('states', 'municipalities.states_id' , '=' , 'states.id')->join('countries', 'states.countries_id' , '=' , 'countries.id');
        $query = $query->whereFullText('products.name', $product_search)->where('product_stores.amount', '>', 0)->where('countries.id', $country_id);

        if($categories_id != 'Categoria'){
            $id_sub_category = SubCategory::where('categories_id', $categories_id)->select('id')->first()->id;
            $query = $query->where('products.sub_categories_id', $id_sub_category);
        }
        return $query->select('stores.name','stores.address','stores.image','stores.description','products.link')->paginate($this->paginate);
    }
}
