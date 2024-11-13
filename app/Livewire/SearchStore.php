<?php

namespace App\Livewire;

use App\Models\CategoryStore;
use App\Models\Municipality;
use App\Models\Sector;
use App\Models\State;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStore extends Component
{
    use WithPagination;

    public $type_store = 'Tienda';
    public $municipalities = [];
    public $states = [];
    public $sectors = [];
    public $data_stores = [];
    public $categories = [];
    public $disabled = true;
    public $empty_stores = false;
    public $new_message = false;
    public $new_message2 = false;
    public $new_message3 = false;

    public $selectedState;
    public $selectedMunicipality;
    public $selectedSector;
    public $name_store;

    public $showFilters = false;
    public $selectedCategoryId;


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
        $this->categories = CategoryStore::where('type_stores_id', env('TIPO_TIENDA_ID'))->get();
    }

    public function render()
    {
        return view('livewire.search-store');
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

    public function selectCategory($categoryId)
    {
        $this->showFilters = true;
        $this->selectedCategoryId = $categoryId;
    }

}
