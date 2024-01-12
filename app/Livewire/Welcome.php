<?php

namespace App\Livewire;

use App\Models\Store;
use Livewire\Component;

class Welcome extends Component
{
    public function render(){
        $stores = Store::all();
        return view('livewire.welcome', ['stores' => $stores]);
    }
}
