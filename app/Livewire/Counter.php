<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\Sector;
use App\Models\State;
use App\Models\Store;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class Counter extends Component{

    use WithPagination;

    public $disabled = true;

    public $state_id;
    public $municipalities_id;
    public $sectors_id;

    public $municipalities = [];
    public $states = [];
    public $sectors = [];

    public function render(){
        return view('livewire.counter');
    }

    public function updateComponent(Request $request){
        $this->state_id = $request->state_id;
        return response()->json($this->states);
    }

    public function changeState(){
        $state_id = $this->state_id;
        $this->municipalities = Municipality::where('states_id', $state_id)->get();
    }

    public function changeMunicipality(){
        $this->sectors = Sector::where('municipalities_id', $this->municipalities_id)->get();
    }

    public function selectSector(){
        if($this->sectors_id != ''){
            $this->disabled = false;
        }else{
            $this->disabled = true;
        }
    }
}