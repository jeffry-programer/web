<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SignalAux;
use Carbon\Carbon;

class RemovePendingAssistance extends Command
{
    // El nombre y la descripción del comando
    protected $signature = 'assistance:remove-pending';
    protected $description = 'Gestionar auxilios viales pendientes y confirmados';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener la fecha y hora actuales menos media hora
        $halfHourAgo = Carbon::now()->subMinutes(30);

        // Buscar los auxilios que no tienen fecha de confirmación y que se crearon hace más de media hora
        $pendingAssistances = SignalAux::whereNull('confirmation')
                                        ->where('created_at', '<=', $halfHourAgo)
                                        ->get();

        // Eliminar los auxilios encontrados
        $deletedCount = 0;
        foreach ($pendingAssistances as $assistance) {
            $assistance->delete();
            $deletedCount++;
        }

        // Obtener la fecha y hora actuales menos una hora
        $oneHourAgo = Carbon::now()->subMinutes(30);

        // Buscar los auxilios que tienen confirmación, fueron confirmados hace más de una hora y no han sido leídos
        $confirmedAssistances = SignalAux::whereNotNull('confirmation')
                                          ->where('confirmation', '<=', $oneHourAgo)
                                          ->where('read', false)
                                          ->get();

        // Actualizar el campo 'read' a true en los auxilios encontrados
        $updatedCount = 0;
        foreach ($confirmedAssistances as $assistance) {
            $assistance->read = true;
            $assistance->save();
            $updatedCount++;
        }

        // Mostrar mensajes en la consola con los resultados
        $this->info("$deletedCount auxilios viales eliminados por estar pendientes más de media hora.");
        $this->info("$updatedCount auxilios viales confirmados hace más de una hora y marcados como leídos.");
    }
}
