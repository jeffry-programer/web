<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class EmailVerified
{
    use SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $user = Auth::user();

        if ($user && $user->profiles_id != 3){
            // Mostrar el modal de bienvenida
            session(['welcome_modal_shown' => true]);
        }
    }
}


