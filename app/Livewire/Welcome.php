<?php

namespace App\Livewire;

use App\Models\Publicy;
use App\Models\SearchUser;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        $date = Carbon::now();
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) use ($date) {
            $query->where('status', true)->where('date_init', '<=', $date)->where('date_end', '>=', $date);
        })->take(6)->get();

        $stores2 = collect();
        $stores3 = collect();

        if (!Auth::check()) {
            $stores2 = SearchUser::with(['store', 'store.municipality'])
                ->limit(9)
                ->get();

            $stores3 = SearchUser::with(['product', 'store'])
                ->limit(9)
                ->get();
        } else {
            $userId = Auth::id();

            $stores2 = SearchUser::where('users_id', $userId)
                ->with(['store', 'store.municipality'])
                ->limit(9)
                ->get();

            $stores3 = SearchUser::where('users_id', $userId)
                ->with(['product', 'store'])
                ->limit(9)
                ->get();
        }

        $array_stores = [];
        $array_stores_final = [];
        $array_products = [];
        $array_products_final = [];

        foreach ($stores2 as $store) {
            if($store->store != null){
                $store_id = $store->store->id; // Asegúrate de que 'store' y 'id' son correctos
                if (!in_array($store_id, $array_stores)) {
                    $array_stores[] = $store_id;
                    $array_stores_final[] = $store;
                }
            }   
        }

        foreach ($stores3 as $product) {
            if($product->product != null){
                $product_id = $product->product->id; // Asegúrate de que 'product' y 'id' son correctos
                if (!in_array($product_id, $array_products)) {
                    $array_products[] = $product_id;
                    $array_products_final[] = $product;
                }
            }
        }


        $publicities = Publicy::where('date_end', '>', $date)
            ->where('status', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        foreach ($stores as $store) {
            $store->address = Crypt::decrypt($store->address);
        }

        foreach ($array_stores_final as $store) {
            $store->store->address = Crypt::decrypt($store->store->address);
        }


        return view('livewire.welcome', [
            'stores' => $stores,
            'stores2' => $array_stores_final,
            'stores3' => $array_products_final,
            'publicities' => $publicities
        ]);
    }
}
