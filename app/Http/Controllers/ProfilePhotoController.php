<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048', // Ajusta la validación según tus necesidades
        ]);

        $route_image = $request->file('profile_photo')->store('public/images-user/'.$request->id);
        $url = Storage::url($route_image);


        auth()->user()->updage(['image' => $path]);

        return redirect()->route('profile.show');
    }
}
