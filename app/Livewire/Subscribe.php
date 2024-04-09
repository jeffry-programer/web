<?php

namespace App\Livewire;

use App\Models\Subscription;
use Carbon\Carbon;
use DragonCode\Contracts\Cashier\Http\Request;
use Illuminate\Http\Request as HttpRequest;
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

    public function subscribe(HttpRequest $request){
        if(!isset(Auth::user()->id)){
            return redirect('/login');
        }
        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $request->stores_id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        return json_encode('ok');
    }

    public function nullSubscribe(HttpRequest $request){
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $request->stores_id)->first();
        $subscribed->delete();
        return json_encode('ok');
    }
}
