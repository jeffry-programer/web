<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifiedEmailApi extends Notification
{

    public $user;
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Código de verificación Tulobuscas')->view('emails.verify-email-api', ['user' => $this->user, 'token' => $this->token]);
    }
}
