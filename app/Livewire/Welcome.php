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
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) use ($date) {
            $query->where('status', true)->where('date_init', '<=', $date)->where('date_end', '>=', $date);
        })->take(6)->get();
        $stores2 = [];
        $stores3 = [];
        $publicities = Publicy::where('date_end', '>', $date)->where('status', true)->inRandomOrder()->take(8)->get();
        return view('livewire.welcome', ['stores' => $stores,'stores2' => $stores2,'stores3' => $stores3, 'publicities' => $publicities]);
    }
}
