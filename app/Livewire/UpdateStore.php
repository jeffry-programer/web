<?php

namespace App\Livewire;

use App\Models\Store;
use Livewire\Component;

class UpdateStore extends Component
{

    public $store;
    public $name;
    public $email;
    public $address;
    public $description;
    public $phone;

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'description' => 'required',
            'phone' => 'required'
        ];
    }

    
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'Por favor, ingrese una dirección de correo electrónico válida.',
            'address.required' => 'El campo dirección es obligatorio.',
            'description.required' => 'La descripción es requerida',
            'phone.required' => 'El numero de telefono es requerido',
        ];
    }

    public function render(){
        $this->name = $this->store->name;
        $this->email = $this->store->email;
        $this->address = $this->store->address;
        $this->description = $this->store->description;
        $this->phone = $this->store->phone;
        return view('livewire.update-store');
    }

    public function updateStore(){
        $this->validate();

        $store = Store::find($this->store->id);
        $store->name = $this->name;
        $store->email = $this->email;
        $store->address = $this->address;
        $store->description = $this->description;
        $store->phone = $this->phone;

        $store->save();
        session()->flash('messageUpdateStore', 'Datos guardados exitosamente');
        return redirect('/tienda/'.str_replace(' ','-', $store->name));
    }
}
