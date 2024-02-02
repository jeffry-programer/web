<?php

namespace App\Livewire;

use App\Models\Publicy;
use App\Models\Store;
use Carbon\Carbon;
use Livewire\Component;

class Welcome extends Component
{
    public function render(){
        $date = Carbon::now();
        $stores = Store::has('promotions')->take(6)->get();
        $publicities = Publicy::where('date_end', '>', $date)->take(6)->get();
        return view('livewire.welcome', ['stores' => $stores, 'publicities' => $publicities]);
    }
}
