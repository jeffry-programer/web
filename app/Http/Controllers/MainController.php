<?php

namespace App\Http\Controllers;

class MainController extends Controller{ 
    public function searchStores(){
        return view('search-stores');
    }

    public function detailStore(){
        return view('detail-store');
    }
}
