<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Livewire\Component;

class Counter extends Component{

    public $states = null;
    public $state;
    public $cities = null;
    public $cityInput;
    public $disabledButton = true;
    public $idCity;

    public $dataCities = [];

    public $country;

    public $count = 0;

    public function search(){
        $this->dataCities = City::join('municipalities', 'cities.municipalities_id', '=', 'municipalities.id')->join('states', 'municipalities.states_id', '=', 'states.id')->where('cities.name','like',$this->cityInput.'%')->where('municipalities.states_id',$this->state)->select('cities.id','cities.name')->distinct()->get();
    }

    public function updateCountry(){
        $country_id = $this->country;
        $states = State::where('countries_id', $country_id)->get();
        if(count($states) > 0){
            $this->states = $states;
        }else{
            $this->states = null;
            $this->cities = null;
        }
    }

    public function updateState(){
        $this->cities = true;
        $this->disabledButton = false;
        $this->search();
    }

    public function render(){
        $countries = Country::all();
        $data = ['countries' => $countries];
        return view('livewire.counter', $data);
    }

}