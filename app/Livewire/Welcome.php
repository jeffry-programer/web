<?php

namespace App\Livewire;

use App\Models\Publicy;
use App\Models\SearchUser;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        $date = Carbon::now();
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) use ($date) {
            $query->where('status', true)->where('date_init', '<=', $date)->where('date_end', '>=', $date);
        })->take(6)->get();

        if(!isset(Auth::user()->id)){
            // Últimas tiendas más buscadas
            $stores2 = [];

            // Últimos productos más buscados por el usuario actual
            $finalArrayProducts = [];
        }else{
            $userId = Auth::user()->id;
            // Últimas tiendas más buscadas
            $stores2 = SearchUser::where('users_id', $userId)->with(['store', 'store.municipality'])->limit(9)->get();            
            $stores3 = SearchUser::where('users_id', $userId)->with(['product', 'store'])->limit(9)->get();  

            $ids = [];
            $finalArrayProducts = [];
            
            foreach($stores3 as $search){
                if(!in_array($search->product->id, $ids)){
                    $ids[] = $search->product->id;
                    $finalArrayProducts[] = $search;
                }
            }
        }

        $publicities = Publicy::where('date_end', '>', $date)->where('status', true)->inRandomOrder()->take(8)->get();
        return view('livewire.welcome', ['stores' => $stores, 'stores2' => $stores2, 'stores3' => $finalArrayProducts, 'publicities' => $publicities]);
    }
}
