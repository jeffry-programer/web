<?php

namespace App\Livewire;

use App\Jobs\EnviarCorreoJob;
use App\Models\AditionalPicturesProduct;
use App\Models\AttentionTime;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Module;
use App\Models\Municipality;
use App\Models\Operation;
use App\Models\Plan;
use App\Models\PlanContracting;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\ProfileOperation;
use App\Models\Promotion;
use App\Models\Publicity;
use App\Models\Publicy;
use App\Models\State;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\Table;
use App\Models\TypePublicity;
use App\Models\User;
use App\Notifications\NotifyAdmin;
use App\Notifications\NotifyUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Validation\Rules\Password;

class UserManagement extends Component
{

    public $category;
    public $subcategories = [];

    protected $listeners = ['deleteUser'];

    public function changeCategory(Request $request)
    {
        if ($request->type == 'category') {
            return json_encode(SubCategory::where('categories_id', $request->id)->get());
        } else {
            return json_encode(Operation::where('modules_id', $request->id)->get());
        }
    }

    public function render()
    {
        $categories = [];
        $modules = [];
        $states = [];

        $name_label = explode("/", $_SERVER['REQUEST_URI'])[3];
        $name_label = str_replace("_", " ", $name_label);
        $name_label = str_replace("%20", " ", $name_label);
        $name_label = str_replace("%C3%B1", "ñ", $name_label);
        $name_table = Table::where('label', $name_label)->first()->name;
        if ($name_table == 'stores' || $name_table == 'users') {
            $states = State::all();
        }
        $data = [];
        $atributes = Schema::getColumnListing($name_table);
        $extra_data = [];

        $atributes = array_diff($atributes, array('current_team_id'));

        foreach ($atributes as $field) {
            if (str_contains($field, '_id')) {
                $table = explode("_id", $field)[0];
                $extra_data[$field]['fields'] = Schema::getColumnListing($table);
                if (DB::table($table)->first() != null) {
                    if (isset(DB::table($table)->first()->name)) {
                        $extra_data[$field]['values'] = DB::table($table)->orderBy('name', 'asc')->get();
                    } else if (isset(DB::table($table)->first()->description)) {
                        $extra_data[$field]['values'] = DB::table($table)->orderBy('description', 'asc')->get();
                    } else {
                        $extra_data[$field]['values'] = DB::table($table)->get();
                    }
                } else {
                    $extra_data[$field]['values'] = DB::table($table)->get();
                }
            }
        }

        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();

        if ($name_label == 'Productos') $categories = Category::all();
        if ($name_label == 'Perfil operaciones') $modules = Module::all();

        return view('livewire.user-management', ['data' => $data, 'label' => $name_label, 'atributes' => $atributes, 'extra_data' => $extra_data, 'tables' => $tables, 'tables2' => $tables2, 'categories' => $categories, 'modules' => $modules, 'states' => $states]);
    }

    public function searchData(Request $request)
    {
        $result = [];
    
        if ($request->table == 'users') {
            // Obtener todos los registros y descifrar los correos
            $users = DB::table($request->table)->select('id', 'email', 'name')->get();
    
            foreach ($users as $user) {
                try {
                    // Intentar descifrar el correo
                    $decryptedEmail = decrypt($user->email);
    
                    // Comparar el correo descifrado con el valor de búsqueda
                    if (stripos($decryptedEmail, $request->value) !== false) {
                        $result[] = [
                            'id' => $user->id,
                            'email' => $decryptedEmail,
                            'name' => $user->name
                        ];
                    }
                } catch (\Exception $e) {
                    // Si ocurre un error al descifrar (por ejemplo, si el dato no estaba cifrado)
                    continue;
                }
            }
    
            return json_encode($result);
        } else {
            // Búsqueda normal para otras tablas
            return json_encode(
                DB::table($request->table)
                    ->where('name', 'like', '%' . $request->value . '%')
                    ->select('id', 'name')
                    ->get()
            );
        }
    }
    

    public function validateRequest(Request $request, $name_table)
    {
        $error = false;
        if (isset($request->name)) {
            if (strlen($request->name) > 100) {
                $error = true;
            }
        }

        if (isset($request->description)) {
            if ($name_table == 'products' || $name_table == 'stores' || $name_table == 'publicities' || $name_table == 'promotions') {
                if (strlen($request->description) > 255) {
                    $error = true;
                }
            } else {
                if (strlen($request->description) > 45) {
                    $error = true;
                }
            }
        }
        return $error;
    }

    public function validateExist(Request $request, $name_table)
    {
        $error = false;
        if (isset($request->name) && $name_table != 'users') {
            if ($name_table == 'sub_categories') {
                if (count(DB::table($name_table)->where('name', $request->name)->where('categories_id', $request->categories_id)->get()) > 0) {
                    $error = true;
                }
            } else if ($name_table == 'municipalities') {
                if (count(DB::table($name_table)->where('name', $request->name)->where('states_id', $request->states_id)->get()) > 0) {
                    $error = true;
                }
            } else if (count(DB::table($name_table)->where('name', $request->name)->get()) > 0) {
                $error = true;
            }
        }
        if (isset($request->description)) {
            if ($name_table == 'operations') {
                if (count(Operation::where('description', $request->description)->where('modules_id', $request->modules_id)->get()) > 0) {
                    $error = true;
                }
            } else if ($name_table == 'sectors') {
                if (count(DB::table($name_table)->where('description', $request->description)->where('municipalities_id', $request->municipalities_id)->get()) > 0) {
                    $error = true;
                }
            } else if ($name_table != 'products' && $name_table != 'stores' && $name_table != 'publicities' && $name_table != 'promotions') {
                if (count(DB::table($name_table)->where('description', $request->description)->get()) > 0) {
                    $error = true;
                }
            }
        }

        if ($name_table == 'users') {
            if (count(DB::table($name_table)->where('email', $request->email)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'stores') {
            if (count(DB::table($name_table)->where('email', $request->email)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'product_stores') {
            if (count(ProductStore::where('products_id', $request->products_id)->where('stores_id', $request->stores_id)->get()) > 0) {
                $error = true;
            }
        }
        return $error;
    }


    public function store(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if ($validate) {
            session()->flash('message', 'Has ingresado un valor demasiado grande!!');
            return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
        }
        $validate =  $this->validateExist($request, $name_table);
        if ($validate) {
            session()->flash('message', 'Este registro ya existe');
            return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'insert into ' . $name_table . ' (';
        $count = 0;
        $image = false;
        foreach ($atributes as $field) {
            if ($field != 'created_at' && $field != 'updated_at' && $field != 'id') {
                if ($count == 0) {
                    $query .= $field;
                } else {
                    $query .= ',' . $field;
                }
                $count++;
            }
        }
        $query .= ',created_at) values (';
        $count = 0;
        foreach ($atributes as $field) {
            if ($field != 'id' && $field != 'created_at' && $field != 'updated_at') {
                if ($field == 'image') {
                    $image = true;
                    $data[$field] = '';
                }
                if ($data[$field] != $request->label && $data[$field] != $request->_token) {
                    if ($count == 0) {
                        $query .= "'" . $data[$field] . "'";
                    } else {
                        if ($request->label == 'Plan contratado' && $field == 'date_end') {
                            $days_plan = Plan::find($request->plans_id)->first()->days;
                            $data[$field] = Carbon::parse($request->date_init)->addDay($days_plan);
                        }
                        if ($field == 'password') $data[$field] = Hash::make($data[$field]);
                        $query .= ",'" . $data[$field] . "'";
                    }
                    $count++;
                }
            }
        }
        $date = Carbon::now();
        $query .= ",'" . $date . "')";
        DB::insert($query);
        session()->flash('message', 'Registro agregado exitosamente!!');
        return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
    }

    public function store2(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if ($validate) {
            return response()->json(['error' => 'Datos incorrectos'], 422);
        }
        $validate =  $this->validateExist($request, $name_table);
        if ($validate) {
            return response()->json(['error' => 'Este registro ya existe'], 422);
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'insert into ' . $name_table . ' (';
        $count = 0;
        foreach ($atributes as $field) {
            if ($field != 'id' && $field != 'created_at' && $field != 'updated_at' && $field != 'remember_token' && $field != 'token' && $field != 'current_team_id' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at' && $field != 'current_team_id') {
                if ($count == 0) {
                    $query .= $field;
                } else {
                    $query .= ',' . $field;
                }
                $count++;
            }
        }
        $query .= ',created_at) values (';
        $count = 0;
        foreach ($atributes as $field) {
            if ($field != 'id' && $field != 'created_at' && $field != 'updated_at' && $field != 'remember_token' && $field != 'token' && $field != 'current_team_id' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at'  && $field != 'current_team_id') {
                if ($field == 'image' || $field == 'image2' || $field == 'resource') {
                    $data[$field] = '';
                }
                if ($field == 'email_verified_at') {
                    $date = Carbon::now();
                    $query .= ",'" . $date . "'";
                    $count++;
                    continue;
                }
                
                if ($data[$field] != $request->label && $data[$field] != $request->_token) {
                    if ($count == 0) {
                        if ($field == 'product_stores_id') $data[$field] = ProductStore::where('products_id', $data['products_id'])->where('stores_id', $data['stores_id'])->first()->id;
                        $query .= "'" . $data[$field] . "'";
                    } else {
                        if ($field == 'password') $data[$field] = Hash::make($data[$field]);
                        if ($field == 'link' && $name_table == 'publicities') {
                            $link_store = Store::find($data['stores_id'])->link;
                            $data[$field] = $link_store;
                        } else if ($field == 'link' && $name_table != 'publicities') {
                            $data[$field] = str_replace(' ', '-', $data['name']);
                        }

                        if($request->label == 'Tiendas' && ($field == 'email' || $field == 'address' || $field == 'phone' || $field == 'RIF')){
                            $data[$field] = Crypt::encrypt($data[$field]);
                        }

                        if($request->label == 'Usuarios' && ($field == 'email' || $field == 'address' || $field == 'phone')){
                            $data[$field] = Crypt::encrypt($data[$field]);
                        }

                        $query .= ",'" . $data[$field] . "'";
                    }
                    $count++;
                }
            }
        }
        $date = Carbon::now();
        $query .= ",'" . $date . "')";
        DB::insert($query);
        $id = DB::table($name_table)->latest('id')->first()->id;

        if ($name_table == 'stores') {
            $type_plan = Plan::where('description', 'Basico')->first();
            $plan = new PlanContracting();
            $plan->plans_id = $type_plan->id;
            $plan->stores_id = $id;
            $plan->date_init = Carbon::now();
            $plan->date_end = Carbon::now()->addDay(intval($type_plan->days));
            $plan->status = true;
            $plan->created_at = Carbon::now();
            $plan->save();
        }
        return json_encode($name_table . '-' . $id);
    }

    public function registerStore(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(0412|0414|0416|0424|0426)\d{7}$/'],
            'RIF' => 'required|max:45|unique:stores',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:stores',
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:100|unique:stores',
            'sectors_id' => 'required',
            'municipalities_id' => 'required',
            'states_id' => 'required',
            'categories_stores_id' => 'required'
        ]);

        $data = $request->all();

        $data['link'] = str_replace(' ', '-', $data['name']);

        $data['address'] = Crypt::encrypt($data['address']);
        $data['email'] = Crypt::encrypt($data['email']);
        $data['RIF'] = Crypt::encrypt($data['RIF']);
        $data['phone'] = Crypt::encrypt($data['phone']);

        // Crear la tienda
        $store = Store::create($data);

        $type_plan = Plan::where('description', 'Basico')->first();

        $plan = new PlanContracting();
        $plan->plans_id = $type_plan->id;
        $plan->stores_id = $store->id;
        $plan->date_init = Carbon::now();
        $plan->date_end = Carbon::now()->addDay(intval($type_plan->days));
        $plan->status = true;
        $plan->created_at = Carbon::now();
        $plan->save();

        // Puedes devolver una respuesta JSON si lo prefieres
        return json_encode('stores' . '-' . $store->id);
    }

    public function registerSucursal(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(0412|0414|0416|0424|0426)\d{7}$/'],
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users|unique:stores',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:100|unique:stores',
            'name2'  => 'required|string|max:100',
            'sectors_id' => 'required',
            'municipalities_id' => 'required',
            'states_id' => 'required',
            'categories_stores_id' => 'required'
        ]);
    
        $data = $request->all();
    
        // Encriptar datos sensibles
        $data['link'] = str_replace(' ', '-', $data['name']);
        $data['address'] = Crypt::encrypt($data['address']);
        $data['RIF'] = Crypt::encrypt(Auth::user()->store->RIF);
        $data['email'] = Crypt::encrypt($data['email']);
        $data['phone'] = Crypt::encrypt($data['phone']);
    
        // Crear el usuario
        $user = User::create([
            'profiles_id' => Auth::user()->profiles_id,
            'name' => $data['name2'],
            'email' => $data['email'], // Se debe desencriptar al mostrarlo
            'password' => Hash::make($request->password),
        ]);
    
        // Crear la tienda
        $data['users_id'] = $user->id;
        $data['sucursal'] = true;
        $store = Store::create($data);
    
        // Asignar plan básico a la tienda
        $type_plan = Plan::where('description', 'Basico')->first();

        $plan = new PlanContracting();
        $plan->plans_id = $type_plan->id;
        $plan->stores_id = $store->id;
        $plan->date_init = Carbon::now();
        $plan->date_end = Carbon::now()->addDay(intval($type_plan->days));
        $plan->status = true;
        $plan->created_at = Carbon::now();
        $plan->save();
    
        // Puedes devolver una respuesta JSON si lo prefieres
        return json_encode('stores' . '-' . $store->id);
    }

    public function registerPromotion(Request $request)
    {
        $request->validate([
            'percent_promotion' => 'required',
            'description' => 'required|min:3|max:100',
            'date_end' => 'required|date|after:date_init|before_or_equal:' . Carbon::now()->addDays(30)->format('Y-m-d'),
            'date_init' => 'required|date',
            'product_stores_id' => 'required'
        ], [
            'date_end.before_or_equal' => 'El sistema le permite máximo 30 dias de promoción.',
            'product_stores_id.required' => 'Por favor seleccione un producto de su tienda'
        ]);

        $product_store = ProductStore::find($request->product_stores_id);

        $promotion = new Promotion();
        $promotion->products_id = $product_store->products_id;
        $promotion->stores_id = $product_store->stores_id;
        $promotion->description = $request->description;
        $promotion->date_init = $request->date_init;
        $promotion->date_end = $request->date_end;
        $promotion->price = $request->percent_promotion;
        $promotion->status = false;
        $promotion->created_at = Carbon::now();
        $promotion->save();

        $this->sendEmailsAdministrators('promoción', $product_store->stores_id);

        return json_encode('promotions' . '-' . $promotion->id);
    }

    public function registerPublicity(Request $request)
    {
        $request->validate([
            'description' => 'required|min:3|max:255',
            'title' => 'required|min:3|max:50',
            'type_publicities_id' => 'required',
        ]);

        $store = Store::find($request->stores_id);

        $publicity = new Publicity();
        $publicity->stores_id = $store->id;
        $publicity->type_publicities_id = $request->type_publicities_id;
        $publicity->title = $request->title;
        $publicity->image = '';
        $publicity->description = $request->description;
        $publicity->link = str_replace(' ', '-', $store->name);
        $publicity->status = false;
        $publicity->date_init = Carbon::now();
        $publicity->date_end = Carbon::now()->addDay(TypePublicity::find($request->type_publicities_id)->amount_days);
        $publicity->created_at = Carbon::now();
        $publicity->save();

        $this->sendEmailsAdministrators('publicidad', $store->id);

        return json_encode('promotions' . '-' . $publicity->id);
    }

    public function sendEmailsAdministrators($type, $store_id)
    {
        $store = Store::find($store_id);
        $users = User::whereHas('profile', function ($query) {
            $query->where('description', 'Administrador');
        })->get();
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                $user->email = Crypt::decrypt($user->email);
                $user->notify(new NotifyAdmin($user, $store, $type));
            }
        }
    }


    public function registerProductStore(Request $request)
    {
        //Agrupar data
        $data = $request->all();

        $products_id = null;

        if ($request->products_id == null) {
            // Validación de los datos
            $request->validate([
                'detail' => 'required|string',
                'reference' => 'required|string|max:45',
                'code' => 'required|string|max:45',
                'description' => 'required|string|max:255',
                'sub_categories_id' => 'required',
                'name' => 'required|string|max:100|unique:products',
            ]);

            if ($request->type_request == 'asociate') {
                $request->validate([
                    'price' => 'required|numeric|min:0',
                    'amount' => 'required|integer|min:1'
                ]);
            }
            // Crear producto
            $data['link'] = str_replace(' ', '-', $data['name']);
            $product = Product::create($data);
            $products_id = $product->id;
        } else {
            if ($request->type_request == 'asociate') {
                $request->validate([
                    'price' => 'required|numeric|min:0',
                    'amount' => 'required|integer|min:1'
                ]);
            }

            $products_id = $request->products_id;
        }

        if ($request->type_request == 'asociate') {
            $data2 = [
                'products_id' => $products_id,
                'stores_id' => $request->stores_id,
                'amount' => $request->amount,
                'price' => $request->price,
            ];

            $product_store_exist = ProductStore::where('stores_id', $request->stores_id)->where('products_id', $request->products_id)->first();
            if ($product_store_exist != null) {
                return json_encode('exist');
            }
            //Crear producto tienda
            ProductStore::create($data2);
        }

        //Puedes devolver una respuesta JSON si lo prefieres
        if ($request->products_id == null) {
            return json_encode('products' . '-' . $product->id);
        } else {
            return json_encode('ok');
        }
    }

    public function delete(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        if ($name_table == 'stores') {
            $store = Store::find($request->id);

            // Eliminar registros asociados
            $store->publicities()->delete(); // Si existe una relación llamada "publicities"
            $store->subscriptions()->delete(); // Si existe una relación llamada "subscription"
            $store->products()->detach(); // Si existe una relación de muchos a muchos llamada "productos"
            $store->promotions()->delete(); // Si existe una relación llamada "promotions"
            $store->planContrating()->delete(); // Si existe una relación llamada "promotions"

            // Eliminar la store
            $store->delete();

            session()->flash('message', 'Registro eliminado exitosamente!!');
            return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
        } else {
            return $this->validateTablesDelete($request, $name_table);
        }
    }

    public function validateTablesDelete(Request $request, $name_table)
    {
        $error = false;
        if ($name_table == 'categories') {
            if (count(SubCategory::where('categories_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'sub_categories') {
            if (count(Product::where('sub_categories_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'brands') {
            if (count(Product::where('brands_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'profiles') {
            if (count(User::where('profiles_id', $request->id)->get()) > 0) {
                $error = true;
            }

            if (count(ProfileOperation::where('profiles_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'operations') {
            if (count(ProfileOperation::where('operations_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'type_stores') {
            if (count(Store::where('type_stores_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'type_stores') {
            if (count(Store::where('type_stores_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'cylinder_capacities') {
            if (count(Product::where('cylinder_capacities_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'models') {
            if (count(Product::where('models_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'boxes') {
            if (count(Product::where('boxes_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'type_products') {
            if (count(Product::where('type_products_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'countries') {
            if (count(State::where('countries_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'states') {
            if (count(Municipality::where('states_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'municipalities') {
            if (count(Municipality::where('municipalities_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }
        if ($name_table == 'municipalities_id') {
            if (count(User::where('municipalities_id', $request->id)->get()) > 0) {
                $error = true;
            }
            if (count(Store::where('municipalities_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'plans') {
            if (count(PlanContracting::where('plans_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'stores' || $name_table == 'days') {
            if (count(AttentionTime::where('stores_id', $request->id)->get()) > 0) {
                $error = true;
            }

            /*if(count(AttentionTime::where('days_id', $request->id)->get()) > 0){
                $error = true;
            }*/
        }

        if ($name_table == 'stores') {
            if (count(Branch::where('stores_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'municipalities_id') {
            if (count(Branch::where('municipalities_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'stores' || $name_table == 'products') {
            if (count(ProductStore::where('stores_id', $request->id)->get()) > 0) {
                $error = true;
            }
            if (count(ProductStore::where('products_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'type_publicities') {
            if (count(Publicy::where('type_publicities_id', $request->id)->get()) > 0) {
                $error = true;
            }
        }

        if ($name_table == 'product_stores') {
            $product_store = ProductStore::find($request->id);
            if (count(Promotion::where('products_id', $product_store->products_id)->where('stores_id', $product_store->stores_id)->get()) > 0) {
                $error = true;
            }
        }

        if ($error) {
            session()->flash('message', 'Este registro tiene sub-registros asociados, debe eliminarlos primero');
            if ($name_table == 'products') {
                return redirect('/admin/products');
            } else {
                return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
            }
        }

        return $this->finalDelete($request, $name_table);
    }

    public function finalDelete(Request $request, $name_table)
    {
        $query = "delete from $name_table where id = $request->id";
        DB::delete($query);
        if ($name_table == 'products') {
            AditionalPicturesProduct::where('products_id', $request->id)->delete();
            $pathDirectory = "public/images-prod/$request->id";
            Storage::deleteDirectory($pathDirectory);
        } else if ($name_table == 'users') {
            $pathDirectory = "public/images-user/$request->id";
            Storage::deleteDirectory($pathDirectory);
        } else if ($name_table == 'stores') {
            $pathDirectory = "public/images-stores/$request->id";
            Storage::deleteDirectory($pathDirectory);
        }
        session()->flash('message', 'Registro eliminado exitosamente!!');
        if ($name_table == 'products') {
            return redirect('/admin/products');
        } else {
            return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
        }
    }

    public function update(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if ($validate) {
            session()->flash('message', 'Has ingresado un valor demasiado grande!!');
            return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
        }
        if (isset($request->name)) {
            if (DB::table($name_table)->find($request->id)->name !== $request->name) {
                $validate = $this->validateExist($request, $name_table);
                if ($validate) {
                    session()->flash('message', 'Este registro ya existe');
                    return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
                }
            }
        }
        if (isset($request->description)) {
            if (DB::table($name_table)->find($request->id)->description !== $request->description) {
                $validate = $this->validateExist($request, $name_table);
                if ($validate) {
                    session()->flash('message', 'Este registro ya existe');
                    return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
                }
            }
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();
        $query = 'update ' . $name_table . ' set ';
        $count = 0;
        foreach ($atributes as $field) {
            if ($field != 'created_at' && $field != 'updated_at' && $field != 'id') {
                if ($count == 0) {
                    $query .= "$field = '" . $data[$field] . "' ";
                } else {
                    if ($field == 'read') {
                        $field = "`read`";
                        $data[$field] = $data['read'];
                    }
                    if ($field == 'password') $data[$field] = Hash::make($data[$field]);
                    $query .= ", $field = '" . $data[$field] . "' ";
                }
                $count++;
            }
        }
        $query .= "where id = $request->id";


        if ($name_table == 'promotions') {
            $status1 = Promotion::find($request->id)->status;
        }
        
        DB::update($query);

        if ($name_table == 'promotions') {
            $status2 = Promotion::find($request->id)->status;
            if ($status1 == false && $status2 == true) {
                $promotion = Promotion::find($request->id);
                $store = Store::find($promotion->stores_id);
                $product = Product::find($promotion->products_id);
                $link = asset('tienda/' . str_replace(' ', '-', $store->name) . '/' . str_replace(' ', '-', $product->name));
                $this->sendEmails($promotion->stores_id, $link);
            }
        }

        if ($name_table == 'promotions') {
            $store = Store::find($request->stores_id);
            $type_notification = 'Promoción';
            $type_notification2 = 'promoción';
            $checkApprove = true;

            $url = '/detail-product/' . $request->products_id . '/' . $request->stores_id;
            $checkApprove = Promotion::find($request->id)->status == true ? true : false;

            if(!$checkApprove){
                session()->flash('message', 'Registro editado exitosamente!!');
                return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
            }

            foreach ($store->subscriptions as $suscriptor) {
                if ($suscriptor->user != null) {
                    $token = $suscriptor->user->token;
                    if (strlen($token) > 10) {
                        $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

                        // Obtener el servicio de mensajería
                        $messaging = $firebase->createMessaging();

                        // Crear el mensaje
                        $message = CloudMessage::fromArray([
                            'token' => $token,  // El token del dispositivo que recibirá la notificación
                            'notification' => [
                                'title' => 'Nueva ' . $type_notification,
                                'body' => $store->name . ' ha creado una nueva ' . $type_notification2,
                            ],
                            'data' => [ // Datos adicionales para manejar la redirección
                                'click_action' => 'OPEN_URL',
                                'url' => $url,  // Ruta donde quieres redirigir al usuario
                            ],
                            'android' => [  // Mover el bloque de Android fuera de 'data'
                                'priority' => 'high',
                            ],
                          ]);

                        // Enviar el mensaje
                        $messaging->send($message);
                    }
                }
            }
        }
        
        session()->flash('message', 'Registro editado exitosamente!!');
        return redirect('/admin/table-management/' . str_replace(' ', '_', $request->label));
    }

    public function update2(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        $validate = $this->validateRequest($request, $name_table);
        if ($validate) {
            abort(404);
        }
        if (isset($request->name)) {
            if (DB::table($name_table)->find($request->id)->name !== $request->name) {
                $validate = $this->validateExist($request, $name_table);
                if ($validate) {
                    abort(404);
                }
            }
        }
        if (isset($request->description)) {
            if (DB::table($name_table)->find($request->id)->description !== $request->description) {
                $validate = $this->validateExist($request, $name_table);
                if ($validate) {
                    abort(404);
                }
            }
        }
        $atributes = Schema::getColumnListing($name_table);
        $data = $request->all();

        if ($name_table == 'publicities') {
            $status1 = Publicity::find($request->id)->status;
        }

        $query = 'update ' . $name_table . ' set ';
        $count = 0;
        foreach ($atributes as $field) {
            if ($field != 'created_at' && $field != 'updated_at' && $field != 'id' && $field != 'email_verified_at' && $field != 'remember_token' && $field != 'token' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at' && $field != 'current_team_id') {
                if ($count == 0) {
                    $query .= "$field = '" . $data[$field] . "' ";
                } else {
                    if ($request->label == 'Publicidad' && $field == 'date_end') {
                        $days_plan = TypePublicity::find($request->type_publicities_id)->amount_days;
                        $data[$field] = Carbon::parse($request->date_init)->addDay($days_plan);
                    }

                    $condition = $request->label == 'Tiendas' && ($field == 'email' || $field == 'address' || $field == 'phone' || $field == 'RIF');
                    $condition2 = $request->label == 'Usuarios' && ($field == 'email' || $field == 'address' || $field == 'phone');
                    if ($field !== 'image' && $field !== 'image2' && !$condition && !$condition2) {
                        if ($field == 'password' && $data[$field] != '') {
                            $data[$field] = Hash::make($data[$field]);
                            $query .= ", $field = '" . $data[$field] . "' ";
                        }
                        if ($field != 'password') {
                            $query .= ", $field = '" . $data[$field] . "' ";
                        }
                    }
                }
                $count++;
            }
        }
        $query .= "where id = $request->id";
        DB::update($query);

        if ($request->label == 'Tiendas') {
            $store = Store::find($request->id);
            $store->email = Crypt::encrypt($request->email);
            $store->address = Crypt::encrypt($request->address);
            $store->phone = Crypt::encrypt($request->phone);
            $store->RIF = Crypt::encrypt($request->RIF);

            $store->save();
        }

        if ($request->label == 'Usuarios') {
            $store = User::find($request->id);
            $store->email = Crypt::encrypt($request->email);
            $store->address = Crypt::encrypt($request->address);
            $store->phone = Crypt::encrypt($request->phone);

            $store->save();
        }

        if ($name_table == 'publicities') {
            $status2 = Publicity::find($request->id)->status;
            if ($status1 == false && $status2 == true) {
                $publicity = Publicity::find($request->id);
                $this->sendEmails($publicity->stores_id, $publicity->link);
            }
        }

        if ($name_table == 'publicities') {
            $store = Store::find($request->stores_id);
            $type_notification = 'Publicidad';
            $type_notification2 = 'publicidad';
            $checkApprove = true;

            $url = '/detail-publicity/' . $request->id;
            $checkApprove = Publicity::find($request->id)->status == true ? true : false;

            if(!$checkApprove){
                return json_encode($name_table . '-' . $request->id);
            }

            foreach ($store->subscriptions as $suscriptor) {
                if ($suscriptor->user != null) {
                    $token = $suscriptor->user->token;
                    if (strlen($token) > 10) {
                        $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

                        // Obtener el servicio de mensajería
                        $messaging = $firebase->createMessaging();

                        // Crear el mensaje
                        $message = CloudMessage::fromArray([
                            'token' => $token,  // El token del dispositivo que recibirá la notificación
                            'notification' => [
                                'title' => 'Nueva ' . $type_notification,
                                'body' => $store->name . ' ha creado una nueva ' . $type_notification2
                            ],
                            'data' => [ // Datos adicionales para manejar la redirección
                                'click_action' => 'OPEN_URL',
                                'url' => $url,  // Ruta donde quieres redirigir al usuario
                            ],
                            'android' => [  // Mover el bloque de Android fuera de 'data'
                                'priority' => 'high',
                            ],
                          ]);

                        // Enviar el mensaje
                        $messaging->send($message);
                    }
                }
            }
        }

        return json_encode($name_table . '-' . $request->id);
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^(0412|0414|0416|0424|0426)\d{7}$/']
        ]);

        $store = Store::find($request->stores_id);
        $store->name = $request->name;
        $store->address = Crypt::encrypt($request->address);
        $store->description = $request->description;
        $store->email = Crypt::encrypt($request->email);
        $store->phone = Crypt::encrypt($request->phone);

        if (isset($request->tipo)) {
            $store->tipo = $request->tipo;
        }

        if (isset($request->capacidad)) {
            $store->capacidad = $request->capacidad;
        }

        if (isset($request->dimensiones)) {
            $store->dimensiones = $request->dimensiones;
        }

        $store->save();
        return json_encode('stores' . '-' . $request->stores_id);
    }

    public function sendEmails($store_id, $link)
    {
        $store = Store::find($store_id);
        $users = $store->users;
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                $user->notify(new NotifyUsers($user, $store, $link));
            }
        }
    }

    public function saveImgs(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv|max:10240', // Hasta 10 MB
        ]);

        if ($request->table == 'products') {
            $route_image = $request->file('file')->store('public/images-prod/' . $request->id);
        } else if ($request->table == 'stores') {
            $route_image = $request->file('file')->store('public/images-stores/' . $request->id);
        } else if ($request->table == 'social_networks') {
            $route_image = $request->file('file')->store('public/images-social/' . $request->id);
        } else if ($request->table == 'promotion') {
            $route_image = $request->file('file')->store('public/images-promotion/' . $request->id);
        } else if ($request->table == 'publicities') {
            $route_image = $request->file('file')->store('public/images-publicity/' . $request->id);
        } else if ($request->table == 'informations') {
            $route_image = $request->file('file')->store('public/files-informations/' . $request->id);
        } else {
            $route_image = $request->file('file')->store('public/images-user/' . $request->id);
        }

        $url = Storage::url($route_image);

        $image = DB::table($request->table)->find($request->id);

        if ($request->table == 'products') {
            if ($image->image == '') {
                $query = "update $request->table set image = '$url' where id = $request->id";
                DB::update($query);
            } else {
                $count = count(AditionalPicturesProduct::where('id', $request->id)->get());
                if ($count == 4) {
                    return false;
                }
                $image = new AditionalPicturesProduct();
                $image->products_id = $request->id;
                $image->image = $url;
                $image->created_at = Carbon::now();
                $image->save();
            }
        } else if ($request->table == 'stores') {
            $store = Store::find($request->id);
            if ($store->image == '') {
                $query = "update $request->table set image = '$url' where id = $request->id";
            } else if ($store->image2 == '') {
                $query = "update $request->table set image2 = '$url' where id = $request->id";
            } else if ($store->image != '' && $store->image2 != '') {
                $image = str_replace('/storage', 'public', $store->image2);
                Storage::delete($image);
                $query = "update $request->table set image = '$url', image2 = '$store->image' where id = $request->id";
            }
            DB::update($query);
        } else if ($request->table == 'informations'){
            $query = "update $request->table set resource = '$url' where id = $request->id";
            DB::update($query);
        } else {
            $image->nameImg = str_replace('/storage', 'public', $request->nameImg);
            Storage::delete($image->nameImg);
            $query = "update $request->table set image = '$url' where id = $request->id";
            DB::update($query);
        }

        $rutaStorage = storage_path();
        chmod($rutaStorage, 0777);
    }

    public function saveImgs2(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        if ($request->table == 'products') {
            $route_image = $request->file('file')->store('public/images-prod/' . $request->id);
        } else if ($request->table == 'stores') {
            $route_image = $request->file('file')->store('public/images-stores/' . $request->id);
        } else if ($request->table == 'social_networks') {
            $route_image = $request->file('file')->store('public/images-social/' . $request->id);
        } else if ($request->table == 'promotion') {
            $route_image = $request->file('file')->store('public/images-promotion/' . $request->id);
        } else if ($request->table == 'publicities') {
            $route_image = $request->file('file')->store('public/images-publicity/' . $request->id);
        } else {
            $route_image = $request->file('file')->store('public/images-user/' . $request->id);
        }

        $url = Storage::url($route_image);

        $image = DB::table($request->table)->find($request->id);

        if ($request->table == 'products') {
            if ($image->image == '') {
                $query = "update $request->table set image = '$url' where id = $request->id";
                DB::update($query);
            } else {
                $count = count(AditionalPicturesProduct::where('id', $request->id)->get());
                if ($count == 4) {
                    return false;
                }
                $image = new AditionalPicturesProduct();
                $image->products_id = $request->id;
                $image->image = $url;
                $image->created_at = Carbon::now();
                $image->save();
            }
        } else if ($request->table == 'stores') {
            $store = Store::find($request->id);
            if ($store->image == '') {
                $query = "update $request->table set image = '$url' where id = $request->id";
            } else if ($store->image2 == '') {
                $query = "update $request->table set image2 = '$url' where id = $request->id";
            } else if ($store->image != '' && $store->image2 != '') {
                $image = str_replace('/storage', 'public', $store->image2);
                Storage::delete($image);
                $query = "update $request->table set image = '$url', image2 = '$store->image' where id = $request->id";
            }
            DB::update($query);
        } else {
            $image->nameImg = str_replace('/storage', 'public', $request->nameImg);
            Storage::delete($image->nameImg);
            $query = "update $request->table set image = '$url' where id = $request->id";
            DB::update($query);
        }
    }

    public function deleteImg(Request $request)
    {
        $name_table = Table::where('label', $request->label)->first()->name;
        $image = DB::table($name_table)->find($request->id)->image;
        if ($name_table == 'products') {
            if ($image == $request->nameImg) {
                $query = "update $name_table set image = '' where id = $request->id";
                DB::update($query);
            } else {
                AditionalPicturesProduct::where('image', $request->nameImg)->delete();
            }
        } else if ($name_table == 'stores') {
            if ($image == $request->nameImg) {
                $query = "update $name_table set image = '' where id = $request->id";
            } else {
                $query = "update $name_table set image2 = '' where id = $request->id";
            }
            DB::update($query);
        } else {
            $query = "update $name_table set image = '' where id = $request->id";
            DB::update($query);
        }

        $request->nameImg = str_replace('/storage', 'public', $request->nameImg);
        Storage::delete($request->nameImg);
    }
}
