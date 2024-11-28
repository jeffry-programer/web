<?php

namespace App\Mail;

use App\Models\Store;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreRegisteredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $store;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Store $store, User $user)
    {
        $this->store = $store;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tulobuscas: Nueva Tienda Registrada')
                    ->view('emails.store_registered')
                    ->with([
                        'store' => $this->store,
                        'user' => $this->user,
                    ]);
    }
}
