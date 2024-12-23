<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    public function registerUser(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'birthdate' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $encrytedEmail = Crypt::encrypt($request->email);

        // Crear el nuevo usuario
        $user = User::create([
            'profiles_id' => $request->profiles_id,
            'name' => $request->name,
            'email' => $encrytedEmail, // Cifrar después de la validación
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
        ]);

        $user->email = $request->email;

        // Enviar notificación de verificación de correo electrónico
        $user->sendEmailVerificationNotification();

        // Autentica al usuario manualmente
        Auth::login($user);

        // Redirige al usuario a su página de inicio o a donde prefieras
        return redirect()->intended('/dashboard');
    }

    public function verify(Request $request, $id)
    {
        // Obtener el usuario por ID
        $user = User::findOrFail($id);
    
        // Verificar si la firma del enlace es válida
        if (!URL::hasValidSignature($request)) {
            return redirect('/login')->withErrors(['message' => 'El enlace de verificación no es válido o ha expirado.']);
        }
    
        // Verificar si el usuario ya está verificado
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('status', 'Tu correo ya está verificado.');
        }
    
        // Marcar el correo electrónico como verificado y disparar el evento
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    
        return redirect('/dashboard')->with('status', '¡Tu correo ha sido verificado exitosamente!');
    }

    public function forgetPassword(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);
    
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
    
        // Usar el correo cifrado para enviar el enlace de restablecimiento
        $status = Password::sendResetLink(['email' => $user->email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
    
    public function resetPassword(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $email = $request->email;
        $newPassword = $request->password;
    
        // Buscar al usuario descifrando el correo
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });
    
        // Si no se encuentra el usuario, devolver error
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Usuario no registrado o correo inválido'])->withInput();
        }
    
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente!');
    }
}
