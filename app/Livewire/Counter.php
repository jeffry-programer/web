<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class Counter extends Component{

    use WithPagination;

    public $dataCities = [];
    public $cityInput;
    public $country_id;
    public $disabled = true;
    public $state_id;
    public $city_id;
    public $name_store;
    public $empty_stores = false;
    public $data_stores = [];
    public $new_message = false;

    public $states = [];

    public function mount(){
        $this->states = State::all();
    }

    public function render(){
        $countries = Country::all();
        return view('livewire.counter', ['countries' => $countries]);
    }

    public function selectCity($id){
        $this->city_id = $id;
        $this->disabled = false;
    }

    public function changeCountry(){
        $this->states = State::where('countries_id', $this->country_id)->get();
    }

    public function changeState(){
        $state_id = $this->state_id;
        $this->dataCities = City::whereHas('municipality', function ($query) use ($state_id) {
            $query->where('states_id', $state_id);
        })->get();
    }

}