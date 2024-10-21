<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;
use App\Models\Advertisement;
use App\Models\Publicity;
use Carbon\Carbon;

class RemoveExpiredPromotionsAndAds extends Command
{
    // El nombre y la descripción del comando
    protected $signature = 'cleanup:remove-expired-promotions-ads';
    protected $description = 'Eliminar promociones y publicidades que hayan vencido hace más de 15 días';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener la fecha actual menos 15 días
        $fifteenDaysAgo = Carbon::now()->subDays(15);

        // Buscar promociones vencidas hace más de 15 días
        $expiredPromotions = Promotion::where('date_end', '<=', $fifteenDaysAgo)->get();

        // Eliminar las promociones vencidas
        $deletedPromotionsCount = 0;
        foreach ($expiredPromotions as $promotion) {
            $promotion->delete();
            $deletedPromotionsCount++;
        }

        // Buscar publicidades vencidas hace más de 15 días
        $expiredAdvertisements = Publicity::where('date_end', '<=', $fifteenDaysAgo)->get();

        // Eliminar las publicidades vencidas
        $deletedAdvertisementsCount = 0;
        foreach ($expiredAdvertisements as $advertisement) {
            $advertisement->delete();
            $deletedAdvertisementsCount++;
        }

        // Mostrar un mensaje en la consola con el número de promociones y publicidades eliminadas
        $this->info("$deletedPromotionsCount promociones eliminadas.");
        $this->info("$deletedAdvertisementsCount publicidades eliminadas.");
    }
}
