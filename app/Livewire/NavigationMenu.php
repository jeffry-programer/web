<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use Livewire\Component;

class NavigationMenu extends Component
{
    public $states = null;

    public $country;

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
        $data = ['categories' => $categories, 'countries' => $countries];
        return view('livewire.navigation-menu', $data);
    }
}
