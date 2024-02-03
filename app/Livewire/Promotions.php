<?php

namespace App\Livewire;

use App\Models\ProductStore;
use App\Models\Promotion;
use App\Models\Publicity;
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

    public function messages()
    {
        return [
            'price_promotion.required' => 'El campo precio es obligatorio.',
            'price_promotion.numeric' => 'El precio debe ser un valor numérico.',
            'price_promotion.regex' => 'El precio debe tener el formato correcto (por ejemplo, 10.99).',
            'productPromotionInput.required' => 'El nombre del producto es requerido',
            'date_init.required' => 'La fecha de inicio es requerida',
            'date_end.required' => 'La fecha fin es requerida',
            'percent_promotion.required' => 'El porcentaje es requerido',
            'description_promotion.required' => 'La descripción es requerida',
            'image_promotion.required' => 'La imagen es requerida'
        ];
    }

    public function render(){
        return view('livewire.promotions');
    }

    public function search(){
        if($this->productPromotionInput == ''){
            $this->dataProductsPromotion = [];
            return false;
        }
        $this->dataProductsPromotion = ProductStore::join('products', 'product_stores.products_id', '=', 'products.id')->where('stores_id', $this->global_store['id'])->where('products.name','like',$this->productPromotionInput.'%')->select('product_stores.id','products.name')->distinct()->get();
    }

    public function select($name, $id_product_store){
        $this->dataProductsPromotion = [];
        $this->productPromotionInput = $name;
        $this->id_product_store = $id_product_store;
    }

    public function savePromotion(){
        $this->validate();

        $route_image = $this->image_promotion->file('file')->store('public/images-promotion/');
        $url = Storage::url($route_image);

        $promotion = new Promotion();
        $promotion->product_stores_id = $this->id_product_store;
        $promotion->date_init = $this->date_init;
        $promotion->date_end = $this->date_end;
        $promotion->price = $this->price_promotion;
        $promotion->image = $url;
        $promotion->description = $this->description_promotion;
        $promotion->created_at = Carbon::now();
        $promotion->save();

        session()->flash('message', 'Promoción creada exitosamente.');
    }

    public function savePublicity(){

        $this->validate([
            'description_ofer' => 'required',
            'image' => 'required',
            'link' => 'required',
            'title' => 'required'
        ]);

        $publicities = new Publicity();
        $publicities->stores_id = $this->global_store['id'];
        $publicities->type_publicities_id = $this->global_store['id'];
        $publicities->title = $this->title;
        $publicities->image = '';
        $publicities->description = $this->description_ofer;
        $publicities->link =  $this->link;
        $publicities->status =  false;
        $publicities->date_init = Carbon::now();
        $publicities->date_end = Carbon::now()->addMonth();
        $publicities->created_at = Carbon::now();
        $publicities->save();

        $route_image = $this->image->store('images/images-publicity/'.$publicities->id, 'public');
        $url = Storage::url($route_image);

        $publicities->image = $url;
        $publicities->save();

        $this->title = null;
        $this->image = null;
        $this->description_ofer = null;
        $this->link = null;

        session()->flash('message2', 'Publicidad creada exitosamente.');
    }
}
