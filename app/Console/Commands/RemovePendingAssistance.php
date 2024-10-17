<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SignalAux;
use Carbon\Carbon;

class RemovePendingAssistance extends Command
{
    // El nombre y la descripción del comando
    protected $signature = 'assistance:remove-pending';
    protected $description = 'Eliminar auxilios viales que llevan más de una hora esperando sin confirmación';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener la fecha y hora actuales menos una hora
        $oneHourAgo = Carbon::now()->subHour();

        // Buscar los auxilios que no tienen fecha de confirmación y que se crearon hace más de una hora
        $pendingAssistances = SignalAux::whereNull('confirmation')
                                        ->where('created_at', '<=', $oneHourAgo)
                                        ->get();

        // Eliminar los auxilios encontrados
        $deletedCount = 0;
        foreach ($pendingAssistances as $assistance) {
            $assistance->delete();
            $deletedCount++;
        }

        // Mostrar un mensaje en la consola con el número de auxilios eliminados
        $this->info("$deletedCount auxilios viales eliminados por estar pendientes más de una hora.");
    }
}
