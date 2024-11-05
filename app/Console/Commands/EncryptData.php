<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Crypt;

class EncryptData extends Command
{
    protected $signature = 'encrypt:data';
    protected $description = 'Encripta los campos de email, phone y address en users y email, address, RIF, phone en stores si no están encriptados';

    public function handle()
    {
        $this->encryptUserData();
        $this->encryptStoreData();

        $this->info('Campos sensibles encriptados correctamente.');
    }

    private function encryptUserData()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($this->isPlainText($user->email)) {
                $user->email = Crypt::encrypt($user->email);
            }

            if ($this->isPlainText($user->phone)) {
                $user->phone = Crypt::encrypt($user->phone);
            }

            if ($this->isPlainText($user->address)) {
                $user->address = Crypt::encrypt($user->address);
            }

            $user->save();
        }
    }

    private function encryptStoreData()
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            if ($this->isPlainText($store->email)) {
                $store->email = Crypt::encrypt($store->email);
            }

            if ($this->isPlainText($store->address)) {
                $store->address = Crypt::encrypt($store->address);
            }

            if ($this->isPlainText($store->RIF)) {
                $store->RIF = Crypt::encrypt($store->RIF);
            }

            if ($this->isPlainText($store->phone)) {
                $store->phone = Crypt::encrypt($store->phone);
            }

            $store->save();
        }
    }

    private function isPlainText($value)
    {
        try {
            Crypt::decrypt($value);
            return false; // Está encriptado
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return true; // No está encriptado
        }
    }
}
