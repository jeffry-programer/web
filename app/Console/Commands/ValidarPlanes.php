<?php

namespace App\Console\Commands;

use App\Models\PlanContracting;
use App\Models\Store;
use App\Models\User;
use App\Notifications\PlanPorVencer;
use App\Notifications\PlanVencidoNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

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
        // Obtener tiendas con plan que vence hoy
        $tiendasConPlanVencido = Store::whereHas('planContrating', function($query) {
            $query->where('date_end', '=', Carbon::today());
        })->get();

        // Obtener tiendas con plan que vence en 5 días
        $tiendasConPlanPorVencer = Store::whereHas('planContrating', function($query) {
            $query->where('date_end', '=', Carbon::today()->addDays(5));
        })->get();

        $user_admin = User::where('profiles_id', 1)->first();

        // Por ejemplo, para las tiendas con plan vencido
        foreach ($tiendasConPlanVencido as $tienda) {
            // Envía correo para plan vencido
            $tienda->status = false;
            $tienda->statusplan = 'No Vigente';
            $tienda->save();

            $plan = PlanContracting::where('stores_id', $tienda->id)->first();
            $plan->status = false;
            $plan->save();

            $tienda->user->notify(new PlanVencidoNotification($tienda, 'Tulobuscas tu plan vence hoy'));
            $user_admin->notify(new PlanVencidoNotification($tienda, 'Vence hoy plan para la tienda '.$tienda->name));

            $this->sendNotification($tienda, 'El plan de tu negocio vence hoy.');
        }

        // Para las tiendas con plan que vence en 5 días
        foreach ($tiendasConPlanPorVencer as $tienda) {
            // Envía correo para plan a punto de vencer
            $tienda->user->notify(new PlanPorVencer($tienda));
            $this->sendNotification($tienda, 'El plan de tu negocio vence en 5 días.');
        }

        $this->info('Planes vencidos validados y tiendas actualizadas exitosamente.');
    }

    public function sendNotification($store, $message){
        $token = $store->user->token;
        if (strlen($token) > 10) {
            $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            // Obtener el servicio de mensajería
            $messaging = $firebase->createMessaging();

            // Crear el mensaje
            $message = CloudMessage::fromArray([
                'token' => $token,  // El token del dispositivo que recibirá la notificación
                'notification' => [
                    'title' => 'Tulobuscas',
                    'body' => $message,
                    'icon' => 'https://tulobuscas.app/images/tulobuscas2.png', // URL de la imagen del ícono de la notificación
                ],
                'data' => [ // Datos adicionales para manejar la redirección
                    'click_action' => 'OPEN_URL',
                    'url' => '/detail-store/' . $store->id,  // Ruta donde quieres redirigir al usuario
                ],
                'android' => [  // Mover el bloque de Android fuera de 'data'
                    'priority' => 'high',
                ],
            ]);

            // Enviar el mensaje
            $messaging->send($message);
        }
    }
}
