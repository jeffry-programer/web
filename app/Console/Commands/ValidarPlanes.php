<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Notifications\PlanPorVencer;
use App\Notifications\PlanVencidoNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ValidarPlanes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validar planes vencidos y actualizar estado de las tiendas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener tiendas con plan vencido
        $tiendasConPlanVencido = Store::whereHas('planContrating', function($query) {
            $query->where('date_end', '<', Carbon::now());
        })->get();

        // Obtener tiendas con plan que vence en 5 días
        $tiendasConPlanPorVencer = Store::whereHas('planContrating', function($query) {
            $query->where('date_end', '>', Carbon::now())->where('date_end', '<=', Carbon::now()->addDays(5));
        })->get();

        // Por ejemplo, para las tiendas con plan vencido
        foreach ($tiendasConPlanVencido as $tienda) {
            // Envía correo para plan vencido
            $tienda->user->notify(new PlanVencidoNotification($tienda));
        }

        // Para las tiendas con plan que vence en 5 días
        foreach ($tiendasConPlanPorVencer as $tienda) {
            // Envía correo para plan a punto de vencer
            $tienda->user->notify(new PlanPorVencer($tienda));
        }

        $this->info('Planes vencidos validados y tiendas actualizadas exitosamente.');
    }
}
