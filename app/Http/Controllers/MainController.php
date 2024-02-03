<?php

namespace App\Http\Controllers;

use App\Models\Publicity;
use App\Models\Store;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller{ 
    public function searchStores(){
        return view('search-stores');
    }

    public function detailStore(){
        return view('detail-store');
    }

    public function admin(){
        return view('admin.dashboard'); 
    }

    public function terminos(){
        return view('terminos');
    }
    public function preguntas(){
        return view('preguntas');
    }
    public function ayuda(){
        return view('ayuda');
    }
    public function politicas(){
        return view('politicas');
    }

    public function contacto(){
        return view('contacto');
    }

    public function publicity($id){

        $date = Carbon::now();

        $publicity = Publicity::find($id);
        $store = Store::find($publicity->stores_id);
        $publicities = Publicity::where('date_end', '>', $date)->take(6)->get();

        $subscribed = false;

        if(isset(Auth::user()->id)){
            $subscribe = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $store->id)->first();
            if($subscribe != false){
                $subscribed = true;
            }

        }
        
        return view('publicity', ['publicity' => $publicity, 'publicities' => $publicities, 'subscribed' => $subscribed, 'store' => $store]);
    }

    public function subscribe(Request $request){
        if(!isset(Auth::user()->id)){
            return redirect('/login');
        }
        
        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $request->id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        //session()->flash('message', 'Suscrito exitosamente!!');
        return redirect('/publicities/'.str_replace(' ', '-', $request->id_p));
    }

    
    public function unsubscribe(Request $request){
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id',$request->id)->first();
        $subscribed->delete();
        //session()->flash('message', 'Suscripción anulada exitosamente!!');
        return redirect('/publicities/'.str_replace(' ', '-', $request->id_p));
    }

    public function register(){
        return view('register');
    }
}
