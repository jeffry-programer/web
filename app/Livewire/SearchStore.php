<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStore extends Component
{

    use WithPagination;

    public $dataCities = [];
    public $cityInput;
    public $country_id;
    public $disabled = true;
    public $city_id;
    public $name_store;

    public $data_stores = [];

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
        $this->dataCities = [];
    }

    public function searchStore(){
        $stores = Store::where('cities_id', $this->city_id);
        if($this->name_store == ""){
            $stores->where('name', 'like', $this->name_store.'%');
        }
        $this->data_stores = $stores->get();
    }
}
