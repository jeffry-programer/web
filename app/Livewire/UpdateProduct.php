<?php

namespace App\Livewire;

use App\Models\ProductStore;
use Livewire\Component;

class UpdateProduct extends Component{
    public $product_store;
    public $store;
    public $amount;
    public $price;
    public $product_detail;

    
    public function rules()
    {
        return [
            'amount' => 'required',
            'price' => 'required',
        ];
    }

    
    public function messages()
    {
        return [
            'amount.required' => 'La cantidad es obligatoria.',
            'price.required' => 'El precio es obligatorio.',
        ];
    }

    public function render(){
        $this->amount = $this->product_store->amount;
        $this->price = $this->product_store->price;
        return view('livewire.update-product');
    }

    public function updateProduct(){
        $this->validate();

        $product = ProductStore::find($this->product_store->id);
        $product->amount = $this->amount;
        $product->price = $this->price;

        $product->save();

        session()->flash('messageUpdateProduct', 'Datos guardados exitosamente');
        return redirect('/tienda/'.str_replace(' ','-', $this->store->name).'/'.str_replace(' ','-', $this->product_detail->name));
    }
}
