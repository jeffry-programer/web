<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;

class SearchStore extends Component
{

    public $dataCities = [];
    public $cityInput;
    public $country_id;
    public $disabled = true;
    public $city_id;

    public function render()
    {
        $countries = Country::all();
        return view('livewire.search-store', ['countries' => $countries]);
    }

    public function search(){
        if($this->cityInput == ''){
            $this->dataCities = [];
            return false;
        }

        $id = $this->country_id;

        $cities = City::whereIn('municipalities_id', function($query) use ($id) {
            $query->select('id')->from('municipalities')->whereIn('states_id', function($query) use ($id) {
                $query->select('id')->from('states')->where('countries_id', $id);
            });
        })->where('name', 'LIKE', $this->cityInput . '%')->get();

        $this->dataCities = $cities;
    }

    public function seleccionar($name, $id){
        $this->cityInput = $name;
        $this->disabled = false;
        $this->city_id = $id;
    }
}
