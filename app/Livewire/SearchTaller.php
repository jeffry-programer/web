<?php

namespace App\Livewire;

use App\Models\Municipality;
use App\Models\Sector;
use App\Models\State;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class SearchTaller extends Component
{

    use WithPagination;

    public $type_store = 'Taller';
    public $municipalities = [];
    public $states = [];
    public $sectors = [];
    public $data_stores = [];
    public $disabled = true;
    public $empty_stores = false;
    public $new_message = false;
    public $new_message2 = false;
    public $new_message3 = false;

    public $selectedState;
    public $selectedMunicipality;
    public $selectedSector;
    public $name_store;

    public function cleanData(){
        $this->disabled = true;
        $this->sectors = [];
        $this->municipalities = [];
        $this->data_stores = [];
        $this->selectedState = '';
        $this->empty_stores = false;
        $this->new_message = false;
        $this->new_message2 = false;
        $this->new_message3 = false;
    }

    public function mount(){
        $this->states = State::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.search-taller');
    }

    public function changeState()
    {
        $this->municipalities = Municipality::where('states_id', $this->selectedState)->orderBy('name', 'asc')->get();
        $this->changeMunicipality();
    }

    public function changeMunicipality(){
        $this->sectors = Sector::where('municipalities_id', $this->selectedMunicipality)->orderBy('description', 'asc')->get();
        $this->disabled = true;
        $this->selectedSector = "";
    }

    public function changeSector(){
        if($this->selectedSector != ''){
            $this->disabled = false;
        }else{
            $this->disabled = true;
        }
    }

    public function searchStore(){
        $type_store = $this->type_store;
        $this->new_message = false;
        $this->new_message2 = false;
        $this->new_message3 = false;
        $this->empty_stores = false;
        $stores = Store::where('status', true)->whereHas('typeStore', function ($query) use ($type_store) {
            $query->where('description', $type_store);
        });

        if($this->selectedSector == "Todos"){
            $sectorIds = Sector::where('municipalities_id', $this->selectedMunicipality)->pluck('id')->toArray();
            $stores->whereIn('sectors_id', $sectorIds);
        }else{
            $stores->where('sectors_id', $this->selectedSector);
        }


        if($this->name_store != ""){
            $stores->whereFullText('name', $this->name_store);
        }

        $response = $stores->get();

        if(count($response) == 0){
            $this->new_message = true;
            $stores = Store::where('status', true)->where('municipalities_id', $this->selectedMunicipality)->whereHas('typeStore', function ($query) use ($type_store) {
                $query->where('description', $type_store);
            });

            if($this->name_store != ""){
                $stores->whereFullText('name', $this->name_store);
            }

            $response = $stores->get();

            if(count($response) == 0){
                $this->new_message = false;
                $this->new_message2 = true;
                $selected_state = $this->selectedState;
                $stores = Store::where('status', true)->whereHas('municipality', function ($query) use ($selected_state) {
                    $query->where('states_id', $selected_state);
                })->whereHas('typeStore', function ($query) use ($type_store) {
                    $query->where('description', $type_store);
                });
    
                if($this->name_store != ""){
                    $stores->whereFullText('name', $this->name_store);
                }
    
                $response = $stores->get();
    
                if(count($response) == 0){
                    $this->new_message2 = false;
                    $this->new_message3 = true;
                    $stores = Store::where('status', true)->whereHas('typeStore', function ($query) use ($type_store) {
                        $query->where('description', $type_store);
                    });
        
                    if($this->name_store != ""){
                        $stores->whereFullText('name', $this->name_store);
                    }
        
                    $response = $stores->get();
        
                    if(count($response) == 0){
                        $this->new_message3 = false;
                        $this->empty_stores = true;
                    }
                }
            }
        }
        $this->data_stores = $response;
    }
}
