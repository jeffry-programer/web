<?php

namespace App\Livewire;

use App\Models\Publicy;
use App\Models\Store;
use Livewire\Component;

class Welcome extends Component
{
    public function render(){
        $stores = Store::has('promotions')->take(6)->get();
        $publicities = Publicy::all();
        return view('livewire.welcome', ['stores' => $stores, 'publicities' => $publicities]);
    }
}
