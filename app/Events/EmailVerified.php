<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

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
        // Mostrar el modal de bienvenida
        session(['welcome_modal_shown' => true]);
    }
}


