<?php

namespace App\Listeners;

use App\Events\NewMessage;
use Pusher\Pusher;

class ProcessNewMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewMessage $event): void
    {
        // Obtiene el mensaje del evento
        $message = $event->message;

        // Configuración de Pusher
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true // Usar TLS para conexiones seguras
        );

        // Crea una nueva instancia de Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        // Canal y evento para Pusher
        $channel = 'chat-channel';
        $event = 'new-message';

        // Datos que se enviarán al cliente
        $data = [
            'message' => $message->toArray() // Convertir el mensaje a un array
        ];

        // Emite el evento a Pusher
        $pusher->trigger($channel, $event, $data);
    }
}
