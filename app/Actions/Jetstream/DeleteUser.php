<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        if($user->profile->description == env('TIPO_TIENDA') || $user->profile->description == env('TIPO_TALLER') || $user->profile->description == env('TIPO_GRUA')){
            // Eliminar registros asociados
            $user->store->publicities()->delete(); // Si existe una relación llamada "publicities"
            $user->store->subscriptions()->delete(); // Si existe una relación llamada "subscription"
            $user->store->products()->detach(); // Si existe una relación de muchos a muchos llamada "productos"
            $user->store->promotions()->delete(); // Si existe una relación llamada "promotions"
            $user->store->planContrating()->delete(); // Si existe una relación llamada "promotions"

            $user->store->delete();
        }
        $user->subscriptions()->delete();
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
