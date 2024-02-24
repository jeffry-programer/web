<?php

namespace App\Livewire;

use App\Models\ProductStore;
use App\Models\Promotion;
use App\Models\Publicity;
use App\Models\TypePublicity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Promotions extends Component
{

    use WithFileUploads;
 
    public $global_store = [];
    public $productPromotionInput;
    public $dataProductsPromotion = [];
    public $id_product_store = null;
    public $date_init;
    public $date_end;
    public $price_promotion;
    public $percent_promotion;
    public $description_ofer;
    public $title;
    public $image;
    public $link;
    public $description_promotion;
    public $image_promotion;
    public $condition2;
    public $type_publicity;

    public function rules()
    {
        return [
            'price_promotion' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'date_init' => 'required',
            'productPromotionInput' => 'required',
            'date_end' => 'required',
            'percent_promotion' => 'required',
            'description_promotion' => 'required',
            'image_promotion' => 'required'
        ];
    }

    public function render(){
        $type_publicities = TypePublicity::all();
        $data = [
            'type_publicities' => $type_publicities
        ];
        return view('livewire.promotions', $data);
    }


    public function search(){
        if($this->productPromotionInput == ''){
            $this->dataProductsPromotion = [];
            return false;
        }
        $this->dataProductsPromotion = ProductStore::join('products', 'product_stores.products_id', '=', 'products.id')->where('stores_id', $this->global_store['id'])->where('products.name','like',$this->productPromotionInput.'%')->select('products.id','products.name')->distinct()->get();
    }

    public function select($name, $id_product_store){
        $this->dataProductsPromotion = [];
        $this->productPromotionInput = $name;
        $this->id_product_store = $id_product_store;
    }

    public function savePromotion(){
        $this->validate();

        $promotion = new Promotion();
        $promotion->products_id = $this->id_product_store;
        $promotion->stores_id = $this->global_store['id'];
        $promotion->date_init = $this->date_init;
        $promotion->date_end = $this->date_end;
        $promotion->price = $this->price_promotion;
        $promotion->image = '';
        $promotion->status = false;
        $promotion->description = $this->description_promotion;
        $promotion->created_at = Carbon::now();
        $promotion->save();

        // Generar un nombre único para la imagen
        $imageName = time().'.'.$this->image_promotion->extension();

        // Guardar la imagen en la carpeta de almacenamiento
        $this->image_promotion->storeAs('public/images-promotion/', $imageName);

        // Guardar la ruta de la imagen en la base de datos
        $promotion->image = 'storage/images-promotion/'.$imageName; // Asignar la ruta de la imagen
        $promotion->save();

        // Limpiar el campo de la imagen después de guardar
        $this->reset('image');

        session()->flash('message', 'Promoción creada exitosamente.');

        $this->productPromotionInput = null;
        $this->date_init = null;
        $this->date_end = null;
        $this->price_promotion = null;
        $this->description_promotion = null;
        $this->percent_promotion = null;
        $this->image_promotion = '';
    }

    public function savePublicity(){
        $this->validate([
            'type_publicity' => 'required',
            'description_ofer' => 'required',
            'image' => 'required',
            'link' => 'required',
            'title' => 'required'
        ]);

        $publicities = new Publicity();
        $publicities->stores_id = $this->global_store['id'];
        $publicities->type_publicities_id = $this->type_publicity;
        $publicities->title = $this->title;
        $publicities->image = '';
        $publicities->description = $this->description_ofer;
        $publicities->link =  $this->link;
        $publicities->status =  false;
        $publicities->date_init = Carbon::now();
        $publicities->date_end = Carbon::now()->addDay(TypePublicity::find($this->type_publicity)->amount_days);
        $publicities->created_at = Carbon::now();
        $publicities->save();

        // Generar un nombre único para la imagen
        $imageName = time().'.'.$this->image->extension();
        $this->image->storeAs('public/images-publicity/', $imageName);

        $publicities->image = 'storage/images-publicity/'.$imageName; // Asignar la ruta de la imagen
        $publicities->save();

        $this->title = null;
        $this->image = null;
        $this->description_ofer = null;
        $this->link = null;

        session()->flash('message2', 'Publicidad creada exitosamente.');
    }
}
