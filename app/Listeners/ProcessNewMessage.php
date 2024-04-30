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
            'cluster' => 'sa1',
            'useTLS' => true // Usar TLS para conexiones seguras
        );

        // Crea una nueva instancia de Pusher
        $pusher = new Pusher(
            'e23d61d2d0481c4e1ed1',
            '61c35688a5288cd07b7c',
            '1792757',
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
