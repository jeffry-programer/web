<?php

namespace App\Livewire;

use App\Models\Box;
use App\Models\Brand;
use App\Models\Category;
use App\Models\cylinderCapacity;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\SubCategory;
use App\Models\TypeProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssociateProduct extends Component{
    public $dataProducts = [];
    public $product_id = null;
    public $store;
    public $amount;
    public $price;

    public $productInput;
    public $nextSteep = false;

    public function render(){
        $categories = Category::all();
        $sub_categories = SubCategory::all();
        $cylinder_capacities = cylinderCapacity::all();
        $models = DB::table('models')->get();
        $boxes = Box::all();
        $type_products = TypeProduct::all();
        $brands = Brand::all();
        $products = Product::all();
        return view('livewire.associate-product', ['categories' => $categories, 'sub_categories' => $sub_categories, 'cylinder_capacities' => $cylinder_capacities, 'models' => $models, 'boxes' => $boxes, 'type_products' => $type_products, 'brands' => $brands, 'products' => $products]);
    }

    public function select($name, $id_product_store){
        $this->dataProducts = [];
        $this->productInput = $name;
        $this->product_id = $id_product_store;
    }

    public function search(){
        if($this->productInput == ''){
            $this->dataProducts = [];
            return false;
        }
        $this->dataProducts = Product::where('products.name','like',$this->productInput.'%')->select('products.id','products.name')->distinct()->get();
    }

    public function associate(){
        if($this->nextSteep == false){
            $this->nextSteep = true;
            return false;
        }
        
        $product_store = new ProductStore();
        $product_store->products_id = $this->product_id;
        $product_store->stores_id = $this->store->id;
        $product_store->amount = $this->amount;
        $product_store->price = $this->price;
        $product_store->created_at = Carbon::now();

        $product_store->save();

        session()->flash('associateProduct', 'Producto asociado exitosamente!!');

        return redirect('/tienda/'.str_replace(' ', '-', $this->store->name));
    }
}
