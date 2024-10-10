<?php

namespace App\Listeners;

use App\Events\NewMessage2;
use Pusher\Pusher;

class ProcessNewMessage2
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(NewMessage2 $event): void
    {
        // Obtiene el mensaje del evento
        $data = $event->data;

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
        $event = 'new-message2';

        // Emite el evento a Pusher
        $pusher->trigger($channel, $event, $data);
    }
}
