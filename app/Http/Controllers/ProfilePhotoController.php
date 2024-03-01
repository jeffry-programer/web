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

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        auth()->user()->update(['profile_photo_path' => $path]);

        return redirect()->route('profile.show');
    }
}
