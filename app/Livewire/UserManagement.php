<?php

namespace App\Livewire;

use App\Models\AditionalPicturesProduct;
use App\Models\AttentionTime;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Municipality;
use App\Models\Plan;
use App\Models\PlanContracting;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\ProfileOperation;
use App\Models\Promotion;
use App\Models\Publicy;
use App\Models\State;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserManagement extends Component
{

    public $category;
    public $subcategories = [];

    protected $listeners = ['deleteUser'];

    public function changeCategory(Request $request){
        return json_encode(SubCategory::where('categories_id', $request->id)->get());
    }

    public function render(){
        $categories = [];
        $name_label = explode("/", $_SERVER['REQUEST_URI'])[3];
        $name_label = str_replace("_", " ", $name_label);
        $name_label = str_replace("%20", " ", $name_label);
        $name_table = Table::where('label', $name_label)->first()->name;
        if($name_table == 'products'){
            $data = Product::all();
        }else{
            $data = DB::table($name_table)->get();
        }
        $atributes = Schema::getColumnListing($name_table);
        $extra_data = [];

        $atributes = array_diff($atributes, array('current_team_id'));

        foreach($atributes as $field){
            if(str_contains($field, '_id')){
                $table = explode("_id", $field)[0];
                $extra_data[$field]['fields'] = Schema::getColumnListing($table);
                $extra_data[$field]['values'] = DB::table($table)->get();
            }
        }

        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();

        if($name_label == 'Productos') $categories = Category::all();
        return view('livewire.user-management', ['data' => $data, 'label' => $name_label, 'atributes' => $atributes, 'extra_data' => $extra_data, 'tables' => $tables, 'tables2' => $tables2, 'categories' => $categories]);
    }

    public function searchData(Request $request){
        if($request->table == 'users'){
            return json_encode(DB::table($request->table)->where('email', 'like', '%'.$request->value.'%')->select('id','email')->get());
        }else{
            return json_encode(DB::table($request->table)->where('name', 'like', '%'.$request->value.'%')->select('id','name')->get());
        }
    }

    public function validateRequest(Request $request, $name_table){
        $error = false;
        if(isset($request->name)){
            if(strlen($request->name) > 100){
                $error = true;
            }
        }
        if(isset($request->description)){
            if($name_table == 'products' || $name_table == 'stores'){
                if(strlen($request->description) > 100){
                    $error = true;
                }
            }else{
                if(strlen($request->description) > 100){
                    $error = true;
                }
            }
        }
        return $error;
    }

    public function validateExist(Request $request, $name_table){
        $error = false;
        if(isset($request->name)){
            if(count(DB::table($name_table)->where('name',$request->name)->get()) > 0){
                $error = true;
            }
        }
        if(isset($request->description)){
            if($name_table != 'products' && $name_table != 'stores' && $name_table != 'publicities' && $name_table != 'promotions'){
                if(count(DB::table($name_table)->where('description',$request->description)->get()) > 0){
                    $error = true;
                }
            }
        }
        if($name_table == 'product_stores'){
            if(count(ProductStore::where('products_id', $request->products_id)->where('stores_id', $request->stores_id)->get()) > 0){
                $error = true;
            }
        }
        return $error;
    }
    

    public function store(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if($validate){
            session()->flash('message', 'Has ingresado un valor demasiado grande!!');
            return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
        }
        $validate =  $this->validateExist($request, $name_table);
        if($validate){
            session()->flash('message', 'Este registro ya existe');
            return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'insert into '.$name_table. ' (';
        $count = 0;
        $image = false;
        foreach($atributes as $field){
            if($field != 'created_at' && $field != 'updated_at' && $field != 'id'){
                if($count == 0){
                    $query .= $field;
                }else{
                    $query .= ','.$field;
                }
                $count++;
            }
        }
        $query .= ',created_at) values (';
        $count = 0;
        foreach($atributes as $field){
            if($field != 'id' && $field != 'created_at' && $field != 'updated_at'){
                if($field == 'image'){
                    $image = true;
                    $data[$field] = '';
                } 
                if($data[$field] != $request->label && $data[$field] != $request->_token){
                    if($count == 0){
                        $query .= "'".$data[$field]."'";
                    }else{
                        if($request->label == 'Plan contratado' && $field == 'date_end'){
                            $days_plan = Plan::find($request->plans_id)->first()->days;
                            $data[$field] = Carbon::parse($request->date_init)->addDay($days_plan);
                        }
                        if($field == 'password') $data[$field] = Hash::make($data[$field]);
                        $query .= ",'".$data[$field]."'";
                    }
                    $count++;
                }
            }
        }
        $date = Carbon::now();
        $query .= ",'".$date."')";
        DB::insert($query);
        session()->flash('message', 'Registro agregado exitosamente!!');
        return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
    }

    public function store2(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if($validate){
            abort(404);
        }
        $validate =  $this->validateExist($request, $name_table);
        if($validate){
            abort(404);
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'insert into '.$name_table. ' (';
        $count = 0;
        foreach($atributes as $field){
            if($field != 'id' && $field != 'created_at' && $field != 'updated_at' && $field != 'remember_token' && $field != 'current_team_id'){
                if($count == 0){
                    $query .= $field;
                }else{
                    $query .= ','.$field;
                }
                $count++;
            }
        }
        $query .= ',created_at) values (';
        $count = 0;
        foreach($atributes as $field){
            if($field != 'id' && $field != 'created_at' && $field != 'updated_at' && $field != 'remember_token' && $field != 'current_team_id'){
                if($field == 'image' || $field == 'image2'){
                    $data[$field] = '';
                } 
                if($data[$field] != $request->label && $data[$field] != $request->_token){
                    if($count == 0){
                        if($field == 'product_stores_id') $data[$field] = ProductStore::where('products_id', $data['products_id'])->where('stores_id', $data['stores_id'])->first()->id;
                        $query .= "'".$data[$field]."'";
                    }else{
                        if($field == 'password') $data[$field] = Hash::make($data[$field]);
                        if($field == 'email_verified_at') $data[$field] = Carbon::now();
                        if($field == 'link' && $name_table == 'publicities'){
                            $link_store = Store::find($data['stores_id'])->link;
                            $data[$field] = $link_store;
                        }else if($field == 'link' && $name_table != 'publicities'){
                            $data[$field] = str_replace(' ','-', $data['name']);
                        } 

                        $query .= ",'".$data[$field]."'";
                    }
                    $count++;
                }
            }
        }
        $date = Carbon::now();
        $query .= ",'".$date."')";
        DB::insert($query);
        $id = DB::table($name_table)->latest('id')->first()->id;
        return json_encode($name_table.'-'.$id);
    }

    public function registerStore(Request $request){
        // Validación de los datos
        $request->validate([
            'cities_id' => 'required',
            'name' => 'required|string|max:45|unique:stores',
            'description' => 'required|string|max:100',
            'email' => 'required|email|unique:stores',
            'address' => 'required|max:255',
            'RIF' => 'required|max:45',
            'phone' => ['required', 'regex:/^\+?\d{8,15}$/'], // Agrega la validación del número de teléfono
        ]);

        $data = $request->all();

        $data['link'] = str_replace(' ','-', $data['name']);

        // Crear la tienda
        $store = Store::create($data);

        // Puedes devolver una respuesta JSON si lo prefieres
        return json_encode('stores'.'-'.$store->id);
    }

    public function delete(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        return $this->validateTablesDelete($request, $name_table);
    }

    public function validateTablesDelete(Request $request, $name_table){
        $error = false;
        if($name_table == 'categories'){
            if(count(SubCategory::where('categories_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'sub_categories'){
            if(count(Product::where('sub_categories_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'brands'){
            if(count(Product::where('brands_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'profiles'){
            if(count(User::where('profiles_id', $request->id)->get()) > 0){
                $error = true;
            }

            if(count(ProfileOperation::where('profiles_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'operations'){
            if(count(ProfileOperation::where('operations_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'type_stores'){
            if(count(Store::where('type_stores_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'type_stores'){
            if(count(Store::where('type_stores_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'cylinder_capacities'){
            if(count(Product::where('cylinder_capacities_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'models'){
            if(count(Product::where('models_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'boxes'){
            if(count(Product::where('boxes_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'type_products'){
            if(count(Product::where('type_products_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'countries'){
            if(count(State::where('countries_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'states'){
            if(count(Municipality::where('states_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'municipalities'){
            if(count(City::where('municipalities_id', $request->id)->get()) > 0){
                $error = true;
            }
        }
        if($name_table == 'cities'){            
            if(count(User::where('cities_id', $request->id)->get()) > 0){
                $error = true;
            }
            if(count(Store::where('cities_id', $request->id)->get()) > 0){
                $error = true;
            }
        }

        if($name_table == 'plans'){
            if(count(PlanContracting::where('plans_id', $request->id)->get()) > 0){
                $error = true;
            }
        }

        if($name_table == 'stores' || $name_table == 'days'){
            if(count(AttentionTime::where('stores_id', $request->id)->get()) > 0){
                $error = true;
            }

            if(count(AttentionTime::where('days_id', $request->id)->get()) > 0){
                $error = true;
            }
        }

        if($name_table == 'stores'){
            if(count(Branch::where('stores_id', $request->id)->get()) > 0){
                $error = true;
            } 
        }

        if($name_table == 'cities'){
            if(count(Branch::where('cities_id', $request->id)->get()) > 0){
                $error = true;
            } 
        }

        if($name_table == 'stores' || $name_table == 'products'){
            if(count(ProductStore::where('stores_id', $request->id)->get()) > 0){
                $error = true;
            }
            if(count(ProductStore::where('products_id', $request->id)->get()) > 0){
                $error = true;
            }
        }

        if($name_table == 'type_publicities'){
            if(count(Publicy::where('type_publicities_id', $request->id)->get()) > 0){
                $error = true;
            }
        }

        if($name_table == 'product_stores'){
            $product_store = ProductStore::find($request->id);
            if(count(Promotion::where('products_id', $product_store->products_id)->where('stores_id', $product_store->stores_id)->get()) > 0){
                $error = true;
            }
        }
        
        if($error){
            session()->flash('message', 'Este registro tiene sub-registros asociados, debe eliminarlos primero');
            return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
        }

        
        return $this->finalDelete($request, $name_table);
    }

    public function finalDelete(Request $request, $name_table){
        $query = "delete from $name_table where id = $request->id";
        DB::delete($query);
        if($name_table == 'products'){
            AditionalPicturesProduct::where('products_id', $request->id)->delete();
            $pathDirectory = "public/images-prod/$request->id";
            Storage::deleteDirectory($pathDirectory);
        }else if($name_table == 'users'){
            $pathDirectory = "public/images-user/$request->id";
            Storage::deleteDirectory($pathDirectory);
        }else if($name_table == 'stores'){
            $pathDirectory = "public/images-stores/$request->id";
            Storage::deleteDirectory($pathDirectory);
        }
        session()->flash('message', 'Registro eliminado exitosamente!!');
        return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
    }

    public function update(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if($validate){
            session()->flash('message', 'Has ingresado un valor demasiado grande!!');
            return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
        }
        if(isset($request->name)){
            if(DB::table($name_table)->find($request->id)->name !== $request->name){
                $validate = $this->validateExist($request, $name_table);
                if($validate){
                    session()->flash('message', 'Este registro ya existe');
                    return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
                }
            }
        }
        if(isset($request->description)){
            if(DB::table($name_table)->find($request->id)->description !== $request->description){
                $validate = $this->validateExist($request, $name_table);
                if($validate){
                    session()->flash('message', 'Este registro ya existe');
                    return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
                }
            }
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'update '.$name_table. ' set ';
        $count = 0;
        foreach($atributes as $field){
            if($field != 'created_at' && $field != 'updated_at' && $field != 'id'){
                if($count == 0){
                    $query .= "$field = '".$data[$field]."' ";
                }else{
                    if($request->label == 'Plan contratado' && $field == 'date_end'){
                        $days_plan = Plan::find($request->plans_id)->first()->days;
                        $data[$field] = Carbon::parse($request->date_init)->addDay($days_plan);
                    }
                    if($field == 'password') $data[$field] = Hash::make($data[$field]);
                    $query .= ", $field = '".$data[$field]."' ";
                }
                $count++;
            }
        }
        $query .= "where id = $request->id";
        DB::update($query);
        session()->flash('message', 'Registro editado exitosamente!!');
        return redirect('/admin/table-management/'.str_replace(' ','_', $request->label));
    }

    public function update2(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if($validate){
            abort(404);
        }
        if(isset($request->name)){
            if(DB::table($name_table)->find($request->id)->name !== $request->name){
                $validate = $this->validateExist($request, $name_table);
                if($validate){
                    abort(404);
                }
            }
        }
        if(isset($request->description)){
            if(DB::table($name_table)->find($request->id)->description !== $request->description){
                $validate = $this->validateExist($request, $name_table);
                if($validate){
                    abort(404);
                }
            }
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'update '.$name_table. ' set ';
        $count = 0;
        foreach($atributes as $field){
            if($field != 'created_at' && $field != 'updated_at' && $field != 'id' && $field != 'email_verified_at' && $field != 'remember_token'){
                if($count == 0){
                    $query .= "$field = '".$data[$field]."' ";
                }else{
                    if($field !== 'image' && $field !== 'image2'){
                        if($field == 'password') $data[$field] = Hash::make($data[$field]);
                        $query .= ", $field = '".$data[$field]."' ";
                    }
                }
                $count++;
            }
        }
        $query .= "where id = $request->id";
        DB::update($query);
        return json_encode($name_table.'-'.$request->id);
    }

    public function saveImgs(Request $request){
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        if($request->table == 'products'){
            $route_image = $request->file('file')->store('public/images-prod/'.$request->id);
        }else if($request->table == 'stores'){
            $route_image = $request->file('file')->store('public/images-stores/'.$request->id);
        }else if($request->table == 'social_networks'){
            $route_image = $request->file('file')->store('public/images-social/'.$request->id);
        }else if($request->table == 'promotion'){
            $route_image = $request->file('file')->store('public/images-promotion/'.$request->id);
        }else if($request->table == 'publicities'){
            $route_image = $request->file('file')->store('public/images-publicity/'.$request->id);
        }else{
            $route_image = $request->file('file')->store('public/images-user/'.$request->id);
        }


        $url = Storage::url($route_image);

        $image = DB::table($request->table)->find($request->id);

        if($request->table == 'products'){
            if($image->image == ''){
                $query = "update $request->table set image = '$url' where id = $request->id";
                DB::update($query);
            }else{
                $count = count(AditionalPicturesProduct::where('id', $request->id)->get());
                if($count == 4){
                    return false;
                }
                $image = new AditionalPicturesProduct();
                $image->products_id = $request->id;
                $image->image = $url;
                $image->created_at = Carbon::now();
                $image->save();
            }
        }else if($request->table == 'stores'){
            $store = Store::find($request->id);
            if($store->image == ''){
                $query = "update $request->table set image = '$url' where id = $request->id";
            }else if($store->image2 == ''){
                $query = "update $request->table set image2 = '$url' where id = $request->id";
            }else if($store->image != '' && $store->image2 != ''){
                $image = str_replace('/storage', 'public', $store->image2);
                Storage::delete($image);  
                $query = "update $request->table set image = '$url', image2 = '$store->image' where id = $request->id";          
            }
            DB::update($query);
        }else{
            $image->nameImg = str_replace('/storage', 'public', $request->nameImg);
            Storage::delete($image->nameImg);
            $query = "update $request->table set image = '$url' where id = $request->id";
            DB::update($query);
        }
    }

    public function saveImgs2(Request $request){        
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $route_image = $request->file('file')->store('public/images-stores/'.$request->id);

        $url = Storage::url($route_image);

        $image = DB::table($request->table)->find($request->id);

        $store = Store::find($request->id);
        if($store->image == ''){
            $query = "update $request->table set image = '$url' where id = $request->id";
        }else if($store->image2 == ''){
            $query = "update $request->table set image2 = '$url' where id = $request->id";
        }else if($store->image != '' && $store->image2 != ''){
            $image = str_replace('/storage', 'public', $store->image2);
            Storage::delete($image);  
            $query = "update $request->table set image = '$url', image2 = '$store->image' where id = $request->id";          
        }

        DB::update($query);
    }

    public function deleteImg(Request $request){
        $name_table = Table::where('label', $request->label)->first()->name;
        $image = DB::table($name_table)->find($request->id)->image;
        if($name_table == 'products'){
            if($image == $request->nameImg){
                $query = "update $name_table set image = '' where id = $request->id";
                DB::update($query);
            }else{
                AditionalPicturesProduct::where('image',$request->nameImg)->delete();
            }
        }else if($name_table == 'stores'){
            if($image == $request->nameImg){
                $query = "update $name_table set image = '' where id = $request->id";
            }else{
                $query = "update $name_table set image2 = '' where id = $request->id";
            }
            DB::update($query);
        }else{
            $query = "update $name_table set image = '' where id = $request->id";
            DB::update($query);
        }

        $request->nameImg = str_replace('/storage', 'public', $request->nameImg);
        Storage::delete($request->nameImg);   
    }       
}
