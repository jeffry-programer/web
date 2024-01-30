<?php

namespace App\Http\Controllers;

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
}
