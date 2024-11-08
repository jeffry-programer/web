<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileInformationForm extends Component
{
    use WithFileUploads;  // Agregar el trait

    public $state = [];
    public $user;
    public $photo;
    public $verificationLinkSent = false;

    public $photoUrl;
    public $isUpdating = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->state = [
            'name' => $this->user->name,
            'email' => decrypt($this->user->email), // Desencriptamos el email
        ];

        $this->photoUrl = $this->user->image ?? null;
    }

    public function updateUserProfileInformation()
    {
        try {
            // Simulación de la actualización (esto puede incluir la actualización de foto y otros datos)
            $this->user->update([
                'name' => $this->state['name'],
                'email' => encrypt($this->state['email']),  // Asegúrate de encriptar el correo
            ]);
    
            // Si hay una foto que actualizar, manejarla aquí
            if ($this->photo) {

                // Eliminar la foto anterior si existe
                if ($this->user->image) {
                    Storage::delete($this->user->image);
                }
                    
                // Subir la nueva foto
                $path = $this->photo->store('public/images-user/' . $this->user->id);
                
                // Actualizar la base de datos con la nueva imagen
                $this->user->update(['image' => $path]);
    
                // Refrescar la URL de la foto
                $this->photoUrl = Storage::url($path);
            }
    
            session()->flash('message', 'Información actualizada correctamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            session()->flash('message', 'Hubo un problema al actualizar la información.');
        }
    }
    

    public function deleteProfilePhoto()
    {
        // Verificar si el usuario tiene una imagen
        if ($this->user->image) {
            // Eliminar la imagen del almacenamiento
            Storage::delete($this->user->image);

            // Eliminar la ruta de la imagen de la base de datos
            $this->user->update(['image' => null]);

            $this->user->save();

            // Refrescar la URL de la foto
            $this->photoUrl = null;

            // Mostrar mensaje de éxito
            session()->flash('message', 'Imagen de perfil eliminada correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.profile-information-form');
    }
}
