<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\State;
use App\Models\Store;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NavigationMenu extends Component
{
    public $states = null;

    public $country;

    public $country_store;

    public $dataCities = [];

    public $cityInput;

    public function updateCountry(){
        $country_id = $this->country;
        $states = State::where('countries_id', $country_id)->get();
        if(count($states) > 0){
            $this->states = $states;
        }else{
            $this->states = null;
        }
    }

    public function render(){
        $categories = Category::all();
        $countries = Country::all();
        $link_store = '';
        $subscribeds = [];

        if(isset(Auth::user()->id)){
            if(Auth::user()->store){
                $link_store = Store::where('users_id', Auth::user()->id)->first()->name;
            }
            $subscribeds = Subscription::where('users_id', Auth::user()->id)->get();
        }

        $products = Product::all();

        $data = ['categories' => $categories, 'countries' => $countries, 'link_store' => $link_store, 'subscribeds' => $subscribeds, 'products' => $products];
        return view('livewire.navigation-menu', $data);
    }
}
