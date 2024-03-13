<?php

namespace App\Livewire;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Subscribe extends Component
{
    public $subscribed;
    public $store;
    public $condition;
    public $condition2;
    public $categories;

    public function render()
    {
        return view('livewire.subscribe');
    }

    public function subscribe(){
        if(!isset(Auth::user()->id)){
            return redirect('/login');
        }
        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $this->store->id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        //session()->flash('message', 'Suscrito exitosamente!!');
        return redirect('/tienda/'.str_replace(' ', '-', $this->store->name));
    }

    public function nullSubscribe(){
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $this->store->id)->first();
        $subscribed->delete();
        //session()->flash('message', 'Suscripción anulada exitosamente!!');
        return redirect('/tienda/'.str_replace(' ', '-', $this->store->name));
    }
}
