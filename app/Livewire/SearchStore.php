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
    public $empty_stores = false;
    public $data_stores = [];
    public $new_message = false;

    public function render()
    {
        $countries = Country::all();
        return view('livewire.search-store', ['countries' => $countries]);
    }

    public function search(){
        $id = $this->country_id;

        $cities = City::whereIn('municipalities_id', function($query) use ($id) {
            $query->select('id')->from('municipalities')->whereIn('states_id', function($query) use ($id) {
                $query->select('id')->from('states')->where('countries_id', $id);
            });
        })->get();

        $this->dataCities = $cities;
    }

    public function searchStore(){
        $stores = Store::whereHas('typeStore', function ($query) {
            $query->where('status', true)->where('cities_id', $this->city_id)->where('description', env('TIPO_TIENDA'));
        });

        if($this->name_store != ""){
            $stores->whereFullText('name', $this->name_store);
        }
        $response = $stores->get();

        if(count($response) == 0){
            $this->new_message = true;
            $response = Store::whereHas('typeStore', function ($query) {
                $query->where('status', true)->where('cities_id', $this->city_id)->where('description', env('TIPO_TIENDA'));
            })->get();
            if(count($response) == 0){
                $this->new_message = false;
                $this->empty_stores = true;
            }else{
                $this->new_message = true;
                $this->empty_stores = false;
            }
        }else{
            $this->new_message = false;
            $this->empty_stores = false;
        }
        $this->data_stores = $response;
    }

    public function selectCity($id){
        $this->city_id = $id;
        $this->disabled = false;
    }
}
