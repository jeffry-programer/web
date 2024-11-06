<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user) {
            $validator->errors()->add('email', 'Usuario no registrado');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            $validator->errors()->add('password', 'Credenciales incorrectas');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Autentica al usuario manualmente
        Auth::login($user);

        // Redirige al usuario a su página de inicio o a donde prefieras
        return redirect()->intended('/dashboard');
    }
}
