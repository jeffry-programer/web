<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Events\NewMessage2;
use App\Mail\StoreRegisteredNotification;
use App\Mail\VerificationEmail;
use App\Models\AditionalPicturesProduct;
use App\Models\Box;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryStore;
use App\Models\Comment;
use App\Models\Conversation;
use App\Models\Country;
use App\Models\cylinderCapacity;
use App\Models\ExchangeRate;
use App\Models\Information;
use App\Models\Message;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Promotion;
use App\Models\Publicity;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Table;
use App\Models\TypeStore;
use App\Models\Modell;
use App\Models\Municipality;
use App\Models\ObsceneWord;
use App\Models\Plan;
use App\Models\PlanContracting;
use App\Models\Renovation;
use App\Models\SearchUser;
use App\Models\Sector;
use App\Models\SignalAux;
use App\Models\State;
use App\Models\SubCategory;
use App\Models\TypeProduct;
use App\Models\TypePublicity;
use App\Models\User;
use App\Models\Work;
use App\Notifications\NotifyAdmin;
use App\Notifications\RecoveryAccount;
use App\Notifications\ResetPasswordApi;
use App\Notifications\VerifiedEmail;
use App\Notifications\VerifiedEmailApi;
use Carbon\Carbon;
use CURLFile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
use IntlDateFormatter;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    /*public function test(){
        $this->getExchangeRate();
    }*/

    public function searchStores()
    {
        return view('search-stores');
    }

    public function detailStore()
    {
        $name_store = str_replace('-', ' ', explode('?', explode('/', $_SERVER['REQUEST_URI'])[2]))[0];
        // Array asociativo con las vocales acentuadas y sus codificaciones URL
        $vocales = array(
            '%C3%A1' => 'á',
            '%C3%a1' => 'á',
            '%C3%89' => 'É',
            '%C3%89' => 'É',
            '%C3%AD' => 'í',
            '%C3%8D' => 'Í',
            '%C3%B3' => 'ó',
            '%C3%93' => 'Ó',
            '%C3%BA' => 'ú',
            '%C3%9A' => 'Ú',
            '%C3%A1' => 'Á',
            '%C3%81' => 'Á',
            '%C3%89' => 'É',
            '%C3%89' => 'É',
            '%C3%8D' => 'Í',
            '%C3%8D' => 'Í',
            '%C3%93' => 'Ó',
            '%C3%93' => 'Ó',
            '%C3%9A' => 'Ú',
            '%C3%9A' => 'Ú',
        );

        // Reemplazar cada vocal acentuada por su equivalente sin codificación URL
        $name_store = str_replace(array_keys($vocales), array_values($vocales), $name_store);
        $store = Store::where('name', $name_store)->first();
        if ($store == null) {
            return redirect('/');
        }
        $user = User::find($store->users_id);
        if ($user == null) {
            return redirect('/');
        }
        if (isset(explode('/', $_SERVER['REQUEST_URI'])[3])) {
            $link_product = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];
            $product = Product::where('name', str_replace('-', ' ', $link_product))->first();
            if ($product == null) {
                return redirect('/tienda/' . str_replace(' ', '-', $store->name));
            }
        }
        $link_product = "";
        if (isset(explode('/', $_SERVER['REQUEST_URI'])[3])) {
            $link_product = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];
            $product_detail = Product::where('name', str_replace('-', ' ', $link_product))->first();
            $product_store = ProductStore::where('stores_id', $store->id)->where('products_id', $product_detail->id)->first();
            if ($product_store == null) return redirect('/tienda/' . str_replace(' ', '-', $store->name));
        }
        return view('detail-store');
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function terminos()
    {
        return view('terminos');
    }
    public function preguntas()
    {
        return view('preguntas');
    }
    public function ayuda()
    {
        return view('ayuda');
    }
    public function politicas()
    {
        return view('politicas');
    }

    public function contacto()
    {
        return view('contacto');
    }

    public function publicity($id)
    {

        $date = Carbon::now();

        $publicity = Publicity::find($id);
        if ($publicity == null) {
            return redirect('/');
        }
        $store = Store::find($publicity->stores_id);
        $publicities = Publicity::where('date_end', '>', $date)->where('status', true)->inRandomOrder()->get();

        $subscribed = false;

        if (isset(Auth::user()->id)) {
            $subscribe = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $store->id)->first();
            if ($subscribe != false) {
                $subscribed = true;
            }
        }

        return view('publicity', ['publicity' => $publicity, 'publicities' => $publicities, 'subscribed' => $subscribed, 'store' => $store]);
    }

    public function subscribe(Request $request)
    {
        if (!isset(Auth::user()->id)) {
            return redirect('/login');
        }

        $subscription = new Subscription();
        $subscription->users_id = Auth::user()->id;
        $subscription->stores_id = $request->id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        //session()->flash('message', 'Suscrito exitosamente!!');
        return redirect('/publicities/' . str_replace(' ', '-', $request->id_p));
    }


    public function unsubscribe(Request $request)
    {
        $subscribed = Subscription::where('users_id', Auth::user()->id)->where('stores_id', $request->id)->first();
        $subscribed->delete();
        //session()->flash('message', 'Suscripción anulada exitosamente!!');
        return redirect('/publicities/' . str_replace(' ', '-', $request->id_p));
    }

    public function register()
    {
        return view('register');
    }

    public function registerStore()
    {
        return view('register-store');
    }

    public function registerCauchera(){
        return view('register-cauchera');
    }

    public function registerOtros(){
        return view('register-otros');
    }

    public function registerTaller()
    {
        return view('register-taller');
    }

    public function registerGrua()
    {
        return view('register-grua');
    }

    public function registerDataStore()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', env('TIPO_TIENDA_ID'))->get();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores
        ];
        return view('register-data-store', $array_data);
    }


    public function registerDataTaller()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', env('TIPO_TALLER_ID'))->get();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores
        ];
        return view('register-data-taller', $array_data);
    }

    public function registerDataCauchera(){
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', env('TIPO_CAUCHERA_ID'))->get();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores
        ];
        return view('register-data-cauchera', $array_data);
    }

    public function registerDataOtros(){
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', env('TIPO_OTROS_ID'))->get();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores
        ];
        return view('register-data-otros', $array_data);
    }

    public function registerDataGrua()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', env('TIPO_GRUA_ID'))->get();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores
        ];
        return view('register-data-grua', $array_data);
    }


    public function productStoreMasive()
    {
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::where('status', true)->whereHas('typeStore', function ($query) {
            $query->where('description', env('TIPO_TIENDA'));
        })->get();
        $products = Product::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-store-massive', $data);
    }

    public function productDeleteMasive()
    {
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::all();
        $products = Product::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-delete-massive', $data);
    }

    public function productStoreDeleteMasive()
    {
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $stores = Store::all();
        $products = ProductStore::all();

        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "stores" => $stores,
            "products" => $products
        ];

        return view('product-store-delete-massive', $data);
    }

    public function associteProducts(Request $request)
    {
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);
        $amounts = explode(',', $request->amounts[0]);
        $prices = explode(',', $request->prices[0]);

        array_shift($products_ids);

        foreach ($products_ids as $index => $key) {
            if (count(ProductStore::where('products_id', $key)->where('stores_id', $request->store_id)->get()) > 0) {
                $exist = true;
                continue; // Salta a la siguiente iteración
            }

            $product_store = new ProductStore();
            $product_store->products_id = $key;
            $product_store->stores_id = $request->store_id;
            $product_store->amount = $amounts[$index];
            $product_store->price = $prices[$index];
            $product_store->created_at = Carbon::now();

            $product_store->save();
        }

        if ($exist) {
            session()->flash('info', 'Algunos de los productos que ud selecciono ya estan asociados a esta tienda por lo que no fue necesario asociarlos');
        }

        session()->flash('message', 'Registros agregados exitosamente!!');
        return redirect('/admin/product_store_masive');
    }

    public function deleteProducts(Request $request)
    {
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);

        array_shift($products_ids);

        foreach ($products_ids as $key) {
            if (count(ProductStore::where('products_id', $key)->get()) > 0) {
                $exist = true;
                continue; // Salta a la siguiente iteración
            }

            AditionalPicturesProduct::where('products_id', $key)->delete();
            $pathDirectory = "public/images-prod/$key";
            Storage::deleteDirectory($pathDirectory);

            Product::destroy($key);
        }

        if ($exist) {
            session()->flash('info', 'Algunos de los productos que ud selecciono estan asociados a una tienda por lo que no se pueden eliminar');
        }

        session()->flash('message', 'Registros eliminados exitosamente!!');
        return redirect('/admin/product_delete_masive');
    }

    public function deleteProductStore(Request $request)
    {
        $exist = false;
        $products_ids = explode(',', $request->products_id[0]);

        array_shift($products_ids);

        foreach ($products_ids as $key) {
            $product_id = ProductStore::find($key)->products_id;
            $promotions = Promotion::where('products_id', $product_id)->where('status', true)->get();
            if (count($promotions) > 0) {
                $promotion = Promotion::where('products_id', $product_id)->where('status', true)->first();
                $date_end = Carbon::parse($promotion->date_end);
                $date = Carbon::now();
                if ($date->lte($date_end)) {
                    $exist = true;
                    continue; // Salta a la siguiente iteración
                }
            }

            ProductStore::destroy($key);
        }

        if ($exist) {
            session()->flash('info', 'Algunos de los productos que ud selecciono estan asociados a una promoción vigente por lo que no se pueden eliminar');
        }

        session()->flash('message', 'Registros eliminados exitosamente!!');
        return redirect('/admin/product_store_delete_masive');
    }

    public function products()
    {
        $tables = Table::where('type', 1)->orderBy('label', 'ASC')->get();
        $tables2 = Table::where('type', 2)->get();
        $products = Product::all();
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $cylinder_capacities = cylinderCapacity::all();
        $models = Modell::all();
        $boxes = Box::all();
        $type_products = TypeProduct::all();
        $brands = Brand::all();
        $data = [
            "tables" => $tables,
            "tables2" => $tables2,
            "products" => $products,
            "categories" => $categories,
            "categories" => $categories,
            "subCategories" => $subCategories,
            "cylinder_capacities" => $cylinder_capacities,
            "models" => $models,
            "boxes" => $boxes,
            "type_products" => $type_products,
            "brands" => $brands
        ];

        return view('products', $data);
    }

    public function getMoreProducts(Request $request)
    {
        $products = Store::find($request->store_id)->products()->paginate(6, ['*'], 'page', $request->page);
        return response()->json($products);
    }

    public function getMoreStores(Request $request)
    {
        $type_store = $request->type;
        $new_message = false;
        $new_message2 = false;
        $new_message3 = false;
        $empty_stores = false;
        $categoryId = $request->selectedCategoryId;

        // Base query for stores
        $stores = Store::where('status', true)
            ->whereHas('typeStore', function ($query) use ($type_store) {
                $query->where('description', $type_store);
            });

        // Apply filters based on sector and name_store
        if ($request->selectedSector == "Todos") {
            $sectorIds = Sector::where('municipalities_id', $request->selectedMunicipality)->pluck('id')->toArray();
            $stores->whereIn('sectors_id', $sectorIds);
        } else {
            $stores->where('sectors_id', $request->selectedSector);
        }

        if ($request->name_store != "") {
            $stores->whereFullText('name', $request->name_store);
        }

        if ($categoryId != '' && $categoryId != 0) {
            $stores->where('categories_stores_id', $categoryId);
        }

        // Get the total count of stores before pagination
        $totalStores = $stores->count();

        // Paginate the stores
        $response = $stores->with('municipality')->with('sector')->paginate(6, ['*'], 'page', $request->page);

        if ($response->isEmpty()) {
            $new_message = true;
            $stores = Store::where('status', true)
                ->where('municipalities_id', $request->selectedMunicipality)
                ->whereHas('typeStore', function ($query) use ($type_store) {
                    $query->where('description', $type_store);
                });

            if ($request->name_store != "") {
                $stores->whereFullText('name', $request->name_store);
            }

            if ($categoryId != '' && $categoryId != 0) {
                $stores->where('categories_stores_id', $categoryId);
            }

            $totalStores = $stores->count(); // Update total stores after applying the new filters

            $response = $stores->with('municipality')->with('sector')->paginate(6, ['*'], 'page', $request->page);

            if ($response->isEmpty()) {
                $new_message = false;
                $new_message2 = true;
                $selected_state = $request->selectedState;
                $stores = Store::where('status', true)
                    ->whereHas('municipality', function ($query) use ($selected_state) {
                        $query->where('states_id', $selected_state);
                    })->whereHas('typeStore', function ($query) use ($type_store) {
                        $query->where('description', $type_store);
                    });

                if ($request->name_store != "") {
                    $stores->whereFullText('name', $request->name_store);
                }

                if ($categoryId != '' && $categoryId != 0) {
                    $stores->where('categories_stores_id', $categoryId);
                }

                $totalStores = $stores->count(); // Update total stores after applying the new filters

                $response = $stores->with('municipality')->with('sector')->paginate(6, ['*'], 'page', $request->page);

                if ($response->isEmpty()) {
                    $new_message2 = false;
                    $new_message3 = true;
                    $stores = Store::where('status', true)
                        ->whereHas('typeStore', function ($query) use ($type_store) {
                            $query->where('description', $type_store);
                        });

                    if ($request->name_store != "") {
                        $stores->whereFullText('name', $request->name_store);
                    }

                    if ($categoryId != '' && $categoryId != 0) {
                        $stores->where('categories_stores_id', $categoryId);
                    }

                    $totalStores = $stores->count(); // Update total stores after applying the new filters

                    $response = $stores->with('municipality')->with('sector')->paginate(6, ['*'], 'page', $request->page);

                    if ($response->isEmpty()) {
                        $new_message3 = false;
                        $empty_stores = true;
                    }
                }
            }
        }

        // Check if there are more stores to load
        $hasMoreStores = ($response->currentPage() * $response->perPage()) < $totalStores;

        foreach ($response as $store) {
            $store->address = Crypt::decrypt($store->address);
            $store->RIF = Crypt::decrypt($store->RIF);
            $store->email = Crypt::decrypt($store->email);
            $store->phone = Crypt::decrypt($store->phone);
        }

        // Prepare the response array
        $array_response = [
            'stores' => $response,
            'new_message' => $new_message,
            'new_message2' => $new_message2,
            'new_message3' => $new_message3,
            'empty_stores' => $empty_stores,
            'count_stores' => $totalStores,
            'has_more_stores' => $hasMoreStores
        ];

        // Return the response as JSON
        return response()->json($array_response);
    }



    public function uploadImageStore(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|max:2048'
            ]);

            $store = Store::find($request->stores_id);

            $rutaImagenEliminar = "";

            if ($request->type == 'banner') {
                $rutaImagenEliminar = 'public/images-stores/' . $store->image2;
            } else {
                $rutaImagenEliminar = 'public/images-stores/' . $store->image;
            }

            if (Storage::exists($rutaImagenEliminar)) {
                Storage::delete($rutaImagenEliminar);
            }

            $route_image = $request->file('file')->store('public/images-stores');
            $url = Storage::url($route_image);

            if ($request->type == 'banner') {
                $store->image2 = $url;
            } else {
                $store->image = $url;
            }

            $store->save();

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No se proporcionó ninguna imagen'], 422);
    }

    public function autocompleteProducts(Request $request)
    {
        $search = $request->get('term');
        $products = Product::where('name', 'LIKE', '%' . $search . '%')->take(5)->get();
        return response()->json($products);
    }

    public function autocompleteProductStore(Request $request)
    {
        $search = $request->get('term');
        $store = $request->get('store');
        $products = Product::whereHas('stores', function ($query) use ($store) {
            $query->where('stores.id', $store);
        })->where('name', 'LIKE', '%' . $search . '%')->take(5)->get();
        return response()->json($products);
    }

    public function registerApi(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'birthdate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = strtolower($request->email); // Convertir a minúsculas
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if ($user) {
            return response()->json(['error' => ['user' => ['Este usuario ya existe']]], 422);
        }

        // Limpiar el token FCM si ya existe
        $user_token_exist = User::where('token', $request->token_fcm)->first();

        if ($user_token_exist != false) {
            $user_token_exist->token = '';
            $user_token_exist->save();
        }

        $encrytedEmail = Crypt::encrypt($email);

        // Crear el nuevo usuario
        $user = User::create([
            'profiles_id' => 3,
            'name' => $request->name,
            'email' => $encrytedEmail, // Cifrar después de la validación
            'password' => Hash::make($request->password),
            'token' => $request->token_fcm,
            'birthdate' => $request->birthdate,
        ]);

        $user->email = $email;

        // Enviar notificación
        $user->notify(new VerifiedEmailApi($user, $request->token));

        return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
    }



    public function loginOrRegisterWithGoogle(Request $request)
    {
        // Valida que el correo esté presente
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255', // Nombre recibido de Google
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Buscar usuario por correo
        $email = strtolower($request->email); // Convertir a minúsculas
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user) {
            // Si el usuario no existe, lo creamos con una contraseña vacía
            $user = User::create([
                'name' => $request->name,
                'email' => Crypt::encrypt($request->email),
                'password' => '', // Contraseña vacía ya que está utilizando Google Login
                'profiles_id' => 3, // O el perfil que necesites
            ]);
        }

        $user_token_exist = User::where('token', $request->token_fcm)->first();

        if ($user_token_exist != false) {
            $user_token_exist->token = '';
            $user_token_exist->save();
        }

        $user->session_active = true;
        $user->token = $request->token_fcm;
        $user->save();

        $user->email = Crypt::decrypt($user->email);
        $user->address = !empty($user->address) ? Crypt::decrypt($user->address) : null;
        $user->phone = !empty($user->phone) ? Crypt::decrypt($user->phone) : null;

        // Retornar la información del usuario y el token
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function sendVerifiedEmailApi(Request $request)
    {
        $email = strtolower($request->email); // Convertir a minúsculas
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if ($user->email_verified_at != null) {
            return response()->json(['error' => 'Correo verificado'], 422);
        }

        $user->email = Crypt::decrypt($user->email);

        $user->notify(new VerifiedEmailApi($user, $request->token));
    }

    public function logoutApi(Request $request)
    {
        $user = User::find($request->userId);
        if ($user != false) {
            $user->session_active = false;
            $user->token = '';
            $user->save();
        }

        return response()->json(['user' => $user], 200);
    }

    public function loginApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = strtolower($request->email); // Convertir a minúsculas

        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user) {
            return response()->json(['error' => 'Usuario no registrado', 'email' => $email], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 422);
        }

        if ($user->session_active) {
            return response()->json(['error' => 'Ya tienes una cuenta abierta, por favor ciérrala para continuar ó ingresa al link de recuperar cuenta'], 422);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['error' => 'Por favor verifica tu correo electrónico'], 422);
        }

        // Actualizar token de FCM si es necesario
        if ($userWithToken = User::where('token', $request->token_fcm)->first()) {
            $userWithToken->update(['token' => null]);
        }

        // Activar la sesión y asignar el nuevo token FCM
        $user->update([
            'session_active' => true,
            'token' => $request->token_fcm,
        ]);

        // Obtener tienda y desencriptar datos sensibles del usuario y la tienda si existe
        $store = Store::where('users_id', $user->id)->first();
        $user->email = $email;
        $user->address = $user->address ? Crypt::decrypt($user->address) : null;
        $user->phone = $user->phone ? Crypt::decrypt($user->phone) : null;

        if ($store) {
            $store->email = Crypt::decrypt($store->email);
            $store->address = Crypt::decrypt($store->address);
            $store->RIF = Crypt::decrypt($store->RIF);
            $store->phone = Crypt::decrypt($store->phone);
        }

        return response()->json([
            'user' => $user,
            'store' => $store ? $store->id : null,
        ], 200);
    }



    public function verifiedApi(Request $request)
    {
        $email = strtolower($request->email); // Convertir a minúsculas
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if ($user) {
            // Actualizar la fecha de verificación
            $user->email_verified_at = Carbon::now();
            $user->session_active = true;
            $user->save();
            $user->email = Crypt::decrypt($user->email);
            $user->address = !empty($user->address) ? Crypt::decrypt($user->address) : null;
            $user->phone = !empty($user->phone) ? Crypt::decrypt($user->phone) : null;
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    public function current(Request $request)
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function subscriptionsApi(Request $request)
    {
        $email = strtolower($request->email); // Convertir a minúsculas
        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });
        $subscriptions = Subscription::where('users_id', $user->id)->get();
        $array_subscriptions = array();
        foreach ($subscriptions as $key) {
            $store = Store::find($key->stores_id);
            $conversation = Conversation::where('stores_id', $store->users_id)->where('users_id', $user->id)->first();
            if ($conversation == null && $key->store->users_id != $user->id) {
                $conversation = new Conversation();
                $conversation->users_id = $user->id;
                $conversation->stores_id = $store->users_id;
                $conversation->created_at = Carbon::now();
                $conversation->save();
            }
            if (isset($conversation->id)) {
                $conversation = $conversation->id;
            } else {
                $conversation = 0;
            }
            $store = [
                'id' => $key->id,
                'id_store' => $key->stores_id,
                'name' => $key->store->name,
                'user_img' => $key->store->image,
                'conversation' => $conversation
            ];

            if ($store['user_img'] == null || $store['user_img'] == '') {
                $letter = strtoupper($store['name'][0]);
                $store['user_img'] = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
            } else {
                $store['user_img'] = $store['user_img'];
            }

            $array_subscriptions[] = $store;
        }
        return response()->json($array_subscriptions, 200);
    }

    public function myPublicitiesApi(Request $request)
    {
        $store = Store::find($request->id);
        $publicities = Publicity::where('stores_id', $store->id)->orderBy('created_at', 'desc')->get();
        return response()->json($publicities, 200);
    }

    public function myPromotionsApi(Request $request)
    {
        $store = Store::find($request->id);
        $promotions = Promotion::where('stores_id', $store->id)->orderBy('created_at', 'desc')->get();
        $promotions_final = [];
        foreach ($promotions as $promotion) {
            $promotion->image = $promotion->product->image;
            $promotions_final[] = $promotion;
        }
        return response()->json($promotions_final, 200);
    }

    public function nullSubscription(Request $request)
    {
        $subscription = Subscription::find($request->id);
        $subscription->delete();

        return response()->json('Subscripcion eliminada exitosamente', 200);
    }

    public function nullSubscription2(Request $request)
    {
        $subscription = Subscription::where('stores_id', $request->store_id)->where('users_id', $request->user_id)->delete();
        return response()->json('Subscripcion eliminada exitosamente', 200);
    }

    public function renewPlan(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'store_id' => 'required|exists:stores,id', // Asegúrate de validar el store_id
            'comentary' => 'nullable|string|max:255', // Opcional, si quieres permitir comentarios
        ]);

        // Verifica si ya existe una renovación para esta tienda
        $existingRenovation = Renovation::where('stores_id', $request->store_id)->first();
        if ($existingRenovation) {
            return response()->json(['error' => 'Este negocio ya tiene una renovación pendiente'], 422);
        }

        // Manejar la imagen
        if ($request->hasFile('image')) {
            $route_image = $request->file('image')->store('public/images-renovation');
            $url = Storage::url($route_image);
        } else {
            return response()->json(['message' => 'No se ha subido ninguna imagen'], 400);
        }

        // Lógica para renovar el plan
        $renovation = new Renovation(); // Asegúrate de tener el modelo Renovation importado
        $renovation->stores_id = $request->store_id; // Asigna el store_id
        $renovation->plans_id = $request->plan_id; // Asigna el plan_id
        $renovation->image = $url; // Asigna la ruta de la imagen
        $renovation->comentary = $request->comentary; // Asigna el comentario (puede ser null)
        $renovation->status = false; // O asigna el estado que consideres necesario
        $renovation->created_at = Carbon::now();
        $renovation->save(); // Guarda la entrada en la base de datos

        return response()->json(['message' => 'Plan renovado exitosamente!', 'renovation' => $renovation], 200);
    }

    public function storeDetail(Request $request)
    {
        // Encuentra la tienda por el ID y carga sus relaciones
        $store = Store::with('sector.municipality.state')->find($request->store_id);

        // Si no encuentra la tienda, devolver un error 404
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        // Busca si existe una conversación entre el usuario y la tienda
        $conversation = Conversation::where('stores_id', $store->users_id)
            ->where('users_id', $request->user_id)
            ->first();

        if ($conversation == null) {
            $conversation = Conversation::where('stores_id', $request->user_id)
                ->where('users_id', $store->users_id)
                ->first();
        }

        // Si no existe conversación y los IDs no coinciden, crea una nueva
        if ($conversation == null && $store->users_id != $request->user_id) {
            $conversation = new Conversation();
            $conversation->users_id = $request->user_id;
            $conversation->stores_id = $store->users_id;
            $conversation->created_at = Carbon::now();
            $conversation->save();
        }

        // Verifica si el usuario tiene una suscripción a la tienda
        $subscription = Subscription::where('stores_id', $request->store_id)
            ->where('users_id', $request->user_id)
            ->exists();

        // Obtén los datos de la tienda, sector, ciudad y estado
        $sector = $store->sector;
        $municipality = $sector ? $sector->municipality : null;
        $state = $municipality ? $municipality->state : null;
        $plan_contracting = PlanContracting::where('stores_id', $store->id)->first();
        $renovation = Renovation::where('stores_id', $store->id)->where('status', false)->exists();

        if ($plan_contracting != false) {
            $plan = $plan_contracting->plan->description;
            $fmt = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $fmt->setPattern('dd MMM yyyy');

            $date_init = $fmt->format(new DateTime($plan_contracting->date_init));
            $date_end = $fmt->format(new DateTime($plan_contracting->date_end));

            $date_range = $date_init . ' - ' . $date_end;
        } else {
            $plan = null;
            $date_range = null;
        }

        // Agrega los datos de sector, ciudad y estado al objeto 'store' antes de enviarlo en la respuesta
        $storeData = $store->toArray();  // Convertimos la tienda a un array
        $storeData['sector'] = $sector ? $sector->description : null;
        $storeData['municipality'] = $municipality ? $municipality->name : null;
        $storeData['state'] = $state ? $state->name : null;
        $storeData['plan_contracting'] = $plan . ' (' . $date_range . ')';
        $storeData['renovation'] = $renovation;
        $category = CategoryStore::find($store->categories_stores_id);
        if ($category != false) {
            $storeData['category'] = $category->description;
        } else {
            $storeData['category'] = null;
        }

        $storeData['email'] = Crypt::decrypt($storeData['email']);
        $storeData['address'] = Crypt::decrypt($storeData['address']);
        $storeData['RIF'] = Crypt::decrypt($storeData['RIF']);
        $storeData['phone'] = Crypt::decrypt($storeData['phone']);

        $municipality = Municipality::find($storeData['municipalities_id']);
        $municipalities = Municipality::where('states_id', $municipality->states_id)->get();

        $sector = Sector::find($storeData['sectors_id']);
        $sectors = Sector::where('municipalities_id', $sector->municipalities_id)->get();

        // Obtener la tasa de cambio
        $exchangeRate = $this->getExchangeRate();

        // Obtener los planes y convertir precios a bolívares
        $plans = Plan::all()->map(function ($plan) use ($exchangeRate) {
            $plan->convertedPrice = $plan->price * $exchangeRate; // Conversión a VES
            return $plan;
        });

        $works = Work::where('stores_id', $storeData['id'])->get();

        // Retorna la respuesta con la tienda, suscripción y conversación
        return response()->json([
            'store' => $storeData,
            'works' => $works,
            'plans' => $plans,
            'subscription' => $subscription,
            'conversation' => $conversation,
            'municipalities' => $municipalities,
            'sectors' => $sectors
        ], 200);
    }

    private function getExchangeRate()
    {
        // Intenta obtener el último tipo de cambio
        $exchangeRate = ExchangeRate::latest()->first();

        // Si no hay registro o el último cambio es más viejo que 24 horas
        if (!$exchangeRate || $exchangeRate->updated_at < now()->subDay()) {
            // Obtener la tasa de cambio de la API
            $response = file_get_contents('https://pydolarve.org/api/v1/dollar?page=bcv');
            $data = json_decode($response, true);

            // Almacenar la nueva tasa de cambio
            $exchangeRate = new ExchangeRate();
            $exchangeRate->rate = $data['monitors']['usd']['price'];
            $exchangeRate->updated_at = now();
            $exchangeRate->save();
        }

        return $exchangeRate->rate;
    }


    public function ProductStoreDetail(Request $request)
    {
        /// Valida que el 'store_id' esté presente en la solicitud
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Obtén la tienda específica
        $store = Store::find($request->store_id);

        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        // Obtén todos los productos asociados a esta tienda
        $products = $store->products;

        $today = Carbon::today(); // Asumiendo que estás usando Carbon para manejar fechas

        // Obtén todas las promociones activas para esta tienda
        $activePromotions = Promotion::where('stores_id', $store->id)
            ->where('status', true)
            ->whereDate('date_init', '<=', $today)
            ->whereDate('date_end', '>=', $today)
            ->get()
            ->keyBy('products_id');

        // Añade la propiedad 'has_promotion' a cada producto
        $productsWithPromotions = $products->map(function ($product) use ($activePromotions) {
            $product->has_promotion = $activePromotions->has($product->id);
            return $product;
        });

        // Ordena los productos desde los que tienen promociones hasta los que no las tienen
        $sortedProducts = $productsWithPromotions->sortByDesc('has_promotion')->values();

        return response()->json(['products' => $sortedProducts], 200);
    }

    public function ProductStoreDetails(Request $request)
    {
        $search = urldecode(str_replace('-', ' ', $request->product_search));

        $search = $this->normalizeText(str_replace('-', ' ', $search));

        // Obtén el ID de la ciudad
        $store_id = $request->store_id;

        // Construir la consulta de búsqueda booleana con comillas dobles para coincidencia exacta
        $searchQuery = '"' . $search . '"';

        // Obtener la tienda que coincide con el ID de la tienda proporcionado
        $store = Store::find($store_id);

        // Búsqueda inicial con MATCH ... AGAINST
        $products = $store->products()
            ->selectRaw("products.id, products.name, products.image, MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance", [$search])
            ->whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
            ->orderByDesc('relevance')
            ->get();


        // Si no se encuentran resultados, buscar con coincidencias parciales
        if ($products->isEmpty()) {
            $searchQuery = $search . '*';
            $products = $store->products()
                ->selectRaw("products.id, products.name, products.image, MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance", [$searchQuery])
                ->whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery])
                ->orWhere('name', 'LIKE', "%{$searchQuery}%") // Complementar con búsqueda relajada
                ->orderByDesc('relevance')
                ->get();
        }

        // Retornar los productos encontrados
        return response()->json(['products' => $products, 'search' => $search], 200);
    }

    function normalizeText($text)
    {
        $unwantedArray = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        ];
        return strtr($text, $unwantedArray);
    }

    public function SubscribeStore(Request $request)
    {
        $subscription = new Subscription();
        $subscription->stores_id = $request->store_id;
        $subscription->users_id = $request->user_id;
        $subscription->created_at = Carbon::now();
        $subscription->save();

        return response()->json(['ok' => $subscription], 200);
    }

    public function publicityDetail(Request $request)
    {
        $publicity = Publicity::findOrFail($request->id);
        // Incrementar el contador de vistas
        $publicity->increment('views');
        return response()->json(['publicity' => $publicity], 200);
    }

    public function pubilicitiesDetail(Request $request)
    {
        // Obtener la fecha actual
        $currentDate = Carbon::now()->toDateString(); // Formato: 'YYYY-MM-DD'

        // Obtener las publicidades que cumplan con las condiciones
        $publicities = Publicity::where('id', '!=', $request->id)
            ->where('status', true) // Solo donde status es true
            ->whereDate('date_init', '<=', $currentDate) // date_init menor o igual a la fecha actual
            ->whereDate('date_end', '>=', $currentDate) // date_end mayor o igual a la fecha actual
            ->inRandomOrder()
            ->get();

        return response()->json(['publicities' => $publicities], 200);
    }

    public function updateDataApi(Request $request)
    {
        // Validar formato básico de correo
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = Crypt::encrypt(strtolower($request->email));
        $user->address = Crypt::encrypt($request->address);
        $user->phone = Crypt::encrypt($request->phone);
        $user->save();

        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;

        return response()->json(['user' => $user], 200);
    }

    public function updateDataStoreApi(Request $request)
    {
        $store = Store::find($request->id);
        $store->name = $request->name;
        $store->description = $request->description;
        $store->email = Crypt::encrypt(strtolower($request->email));
        $store->address = Crypt::encrypt($request->address);
        $store->phone = Crypt::encrypt($request->phone);
        $store->municipalities_id = $request->municipalities_id;
        $store->sectors_id = $request->sectors_id;
        $store->save();

        return response()->json(['user' => $store], 200);
    }

    public function uploadImageApi(Request $request)
    {
        if ($request->hasFile('image')) {
            // Obtener el directorio donde se guardarán las imágenes
            $directory = 'public/images-user/' . $request->id;

            // Verificar si existen imágenes antiguas en el directorio
            if (Storage::exists($directory)) {
                // Eliminar todas las imágenes antiguas del directorio
                Storage::deleteDirectory($directory);
            }
            $route_image = $request->file('image')->store('public/images-user/' . $request->id);
            $url = Storage::url($route_image);
            $user = User::find($request->id);
            $user->image = $url;
            $user->save();
            return response()->json(['url' => asset($url), 'url2' => str_replace('/storage/', '', $url)]);
        } else {
            return response()->json(['error' => 'No se ha recibido ninguna imagen'], 400);
        }
    }

    public function uploadImageApi2(Request $request)
    {
        if ($request->hasFile('image')) {
            // Obtener el directorio donde se guardarán las imágenes
            $directory = 'public/images-stores/' . $request->id;

            // Verificar si existen imágenes antiguas en el directorio
            if (Storage::exists($directory)) {
                // Eliminar todas las imágenes antiguas del directorio
                Storage::deleteDirectory($directory);
            }
            $route_image = $request->file('image')->store('public/images-stores/' . $request->id);
            $url = Storage::url($route_image);
            $store = Store::find($request->id);
            $store->image = $url;
            $store->save();
            return response()->json(['url' => asset($url), 'url2' => str_replace('/storage/', '', $url)]);
        } else {
            return response()->json(['error' => 'No se ha recibido ninguna imagen'], 400);
        }
    }

    public function getCountriesApi()
    {
        return Country::all();
    }

    public function getStatesApi($countryId)
    {
        return State::where('countries_id', $countryId)->get();
    }

    public function getCategoriesStoreApi($typeStore)
    {
        $type_store = TypeStore::where('description', $typeStore)->first();
        if ($type_store != null) {
            $categories = CategoryStore::where('type_stores_id', $type_store->id)
                ->orderBy('description', 'asc') // Ordenar por descripción en orden ascendente
                ->get();
        } else {
            $categories = [];
        }
        return response()->json(['categories' => $categories]);
    }

    public function getStates()
    {
        $states = State::all();
        $type_stores = TypeStore::all();
        return response()->json(['states' => $states, 'type_stores' => $type_stores]);
    }

    public function getMunicipalityByState($stateId)
    {
        $state = State::findOrFail($stateId);
        $municipalities = $state->municipalities()->get();
        return response()->json($municipalities);
    }

    public function getSectorsByMunicipality($cityId)
    {
        $sectors = Sector::where('municipalities_id', $cityId)->get();
        return response()->json($sectors);
    }

    public function getProductDetail($productId, $idStore, $idUser)
    {
        $product = Product::find($productId);
        $product_store = ProductStore::where('products_id', $productId)->where('stores_id', $idStore)->first();

        $response = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'detail' => $product->detail,
            'code' => $product->code,
            'brand' => $product->brand->description ?? '',
            'image' => $product->image,
            'amount' => $product_store->amount,
            'price' => $product_store->price,
            'promotion' => Promotion::where('products_id', $productId)
                ->where('stores_id', $idStore) // Suponiendo que esta es la columna correcta para el id de la tienda
                ->where('status', true) // Asegurar que el estado sea true
                ->whereDate('date_init', '<=', now()) // La fecha de inicio debe ser menor o igual a hoy
                ->whereDate('date_end', '>=', now())  // La fecha de fin debe ser mayor o igual a hoy
                ->first()
        ];

        $store = Store::find($idStore);
        $conversation = Conversation::where('stores_id', $store->user->id)->where('users_id', $idUser)->first();
        if ($conversation == null && $store->users_id != $idUser) {
            $conversation = new Conversation();
            $conversation->users_id = $idUser;
            $conversation->stores_id = $store->users_id;
            $conversation->created_at = Carbon::now();
            $conversation->save();
        }

        // Retornar el array con la información
        return response()->json(['product' => $response, 'conversation' => $conversation]);
    }

    public function getVideoDetail($videoId)
    {
        $video_detail = Information::find($videoId);

        // Obtener otros videos aleatoriamente, excluyendo el actual
        $videos = Information::where('id', '!=', $videoId)->inRandomOrder()->get();

        // Devolver la respuesta en formato JSON
        return response()->json([
            'video_detail' => $video_detail,
            'videos' => $videos,
        ], 200);
    }

    public function getProductDetails($id)
    {
        $product = Product::with('brand')->find($id);
        return response()->json(['product' => $product]);
    }

    /*----------------------------------BUSQUEDA PRINCIPAL---------------------------------*/
    public function getStoreSearch(Request $request)
    {
        $search = strtolower(trim(str_replace('-', ' ', $request->query('query'))));
        $municipalityId = $request->municipalityId;
        $stateId = $request->stateId;
        $sectorId = $request->sectorId;
        $locationStores = 'sector'; // Valor por defecto

        // Realiza la búsqueda de productos que coincidan con la búsqueda
        $products = $this->searchProducts($search);

        // Si no hay productos, no se pueden retornar tiendas
        if ($products->isEmpty()) {
            return response()->json(['stores' => [], 'locationStores' => $locationStores], 200);
        }

        // Primero, intentamos buscar en el municipio
        $stores = $this->getStoresFromProducts($products, $municipalityId, $stateId, $sectorId, $locationStores, 'municipality');

        // Si no encontramos tiendas en el municipio, buscamos en el estado
        if ($stores->isEmpty() && $stateId) {
            $stores = $this->getStoresFromProducts($products, null, $stateId, $sectorId, $locationStores, 'state');
        }

        // Si aún no encontramos tiendas, buscamos a nivel nacional
        if ($stores->isEmpty()) {
            $stores = $this->getStoresFromProducts($products, null, null, $sectorId, $locationStores, 'country');
        }

        // Aseguramos que los productos relacionados con las tiendas ya están cargados
        foreach ($stores as $store) {
            // Desencriptamos la dirección solo si es necesario
            $store->address = Crypt::decrypt($store->address);
        }

        // Guardar la búsqueda del usuario
        $this->recordUserSearch($stores, $request->userId);

        return response()->json(['stores' => $stores, 'locationStores' => $locationStores], 200);
    }

    private function searchProducts($search)
    {
        return Product::selectRaw("*, MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance", [$search])
            ->whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
            ->orderByDesc('relevance')
            ->get();
    }

    private function getStoresFromProducts($products, $municipalityId, $stateId, $sectorId, &$locationStores, $locationType)
    {
        // Cargar la relación 'stores.municipality' de los productos
        $products->load('stores.municipality');

        // Recopilar todas las tiendas en una colección
        $stores = collect();

        foreach ($products as $product) {
            if ($product->stores) {
                foreach ($product->stores as $store) {
                    if ($store->status == true) { // Verificar si la tienda está activa
                        $stores->push($store);
                    }
                }
            }
        }

        // Eliminar duplicados basados en la clave 'id'
        $stores = $stores->unique('id');

        // Agregar un producto de prueba en la posición 0 a cada tienda
        $stores->each(function ($store) {
            $store->products = collect([
                (object)[
                    'id' => 0,
                    'name' => 'Producto de prueba',
                    'description' => 'Este es un producto de prueba agregado dinámicamente',
                    'price' => 0.00,
                    'status' => true,
                ]
            ]);
        });

        // Filtrar por ubicación (municipio, estado o sector)
        if ($locationType === 'municipality' && $municipalityId) {
            $stores = $stores->filter(function ($store) use ($municipalityId) {
                return $store->municipality && $store->municipality->id == $municipalityId;
            });
        }

        if ($locationType === 'state' && $stateId) {
            $stores = $stores->filter(function ($store) use ($stateId) {
                return $store->municipality && $store->municipality->states_id == $stateId;
            });
        }

        if ($locationType === 'country' && $sectorId && $sectorId !== 'Todos') {
            $stores = $stores->filter(function ($store) use ($sectorId) {
                return $store->sectors_id == $sectorId;
            });
        }

        // Paginación manual
        $stores = $stores->values(); // Reindexar la colección
        $perPage = 10;
        $page = request()->get('page', 1);
        $paginatedStores = $stores->slice(($page - 1) * $perPage, $perPage);

        // Crear un paginador manual
        $paginatedStores = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedStores,
            $stores->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Establecer el tipo de ubicación para la respuesta
        if ($paginatedStores->isNotEmpty()) {
            $locationStores = $locationType;
        }

        return $paginatedStores;
    }


    private function recordUserSearch($stores, $userId)
    {
        $storeIds = $stores->pluck('id');

        $products = ProductStore::whereIn('stores_id', $storeIds)
            ->with('product')
            ->get();

        foreach ($stores as $store) {
            $productStore = $products->first(function ($item) use ($store) {
                return $item->stores_id == $store->id;
            });

            if ($productStore && $productStore->product) {
                $searchExists = SearchUser::where('products_id', $productStore->products_id)
                    ->where('stores_id', $store->id)
                    ->exists();

                if (!$searchExists) {
                    $search_user = new SearchUser();
                    $search_user->users_id = $userId;
                    $search_user->stores_id = $store->id;
                    $search_user->products_id = $productStore->products_id;
                    $search_user->created_at = now();

                    $search_user->save();
                }
            }
        }
    }

    /*--------------------------------------------------------------------------------------*/

    public function getStoreSearch2(Request $request)
    {
        $query = $request->query('query');
        $municipalityId = $request->municipalityId;
        $stateId = $request->stateId;
        $sectorId = $request->sectorId;
        $locationStores = 'sector';

        // Función para agregar filtros comunes al query
        $applyFilters = function ($storeQuery) use ($request, $query) {
            if (!empty($request->categoryId) && $request->categoryId != '0') {
                $storeQuery->where('categories_stores_id', $request->categoryId);
            }
            if (!empty($query)) {
                $storeQuery->where('name', 'like', '%' . $query . '%');
            }
            return $storeQuery;
        };

        // Construcción del query base
        $storeQuery = Store::where('status', true)
            ->where('municipalities_id', $municipalityId)
            ->where('type_stores_id', $request->type)
            ->with('municipality');

        // Filtrar por sector si aplica
        if ($sectorId !== 'Todos') {
            $storeQuery->where('sectors_id', $sectorId);
        }

        $storeQuery = $applyFilters($storeQuery);

        if ($request->type == env('TIPO_TALLER_ID')) {
            if ($request->categoryId == env('TALLER_MECANICO')) {
                $storeQuery2 = Store::where('status', true)
                    ->where('municipalities_id', $municipalityId)
                    ->where('services', true)
                    ->where('categories_stores_id', env('TIPO_REPUESTOS'))
                    ->with('municipality');

                if ($sectorId !== 'Todos') {
                    $storeQuery2->where('sectors_id', $sectorId);
                }

                // Obtener resultados individuales
                $stores1 = $storeQuery->get();
                $stores2 = $storeQuery2->get();

                // Combinar resultados
                $mergedStores = $stores1->merge($stores2);

                // Implementar paginación manual
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $currentResults = $mergedStores->slice(($currentPage - 1) * $perPage, $perPage);

                $stores = new LengthAwarePaginator(
                    $currentResults,
                    $mergedStores->count(),
                    $perPage,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            } else {
                $categoryStore = CategoryStore::find($request->categoryId);

                $storeQuery2 = Store::where('status', true)
                    ->where('municipalities_id', $municipalityId)
                    ->where('services', true)
                    ->whereHas('category', function ($query) use ($categoryStore) {
                        $query->where('description', $categoryStore->description);
                    })
                    ->with('municipality');

                if ($sectorId !== 'Todos') {
                    $storeQuery2->where('sectors_id', $sectorId);
                }

                // Obtener resultados individuales
                $stores1 = $storeQuery->get();
                $stores2 = $storeQuery2->get();

                // Combinar resultados
                $mergedStores = $stores1->merge($stores2);

                // Implementar paginación manual
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $currentResults = $mergedStores->slice(($currentPage - 1) * $perPage, $perPage);

                $stores = new LengthAwarePaginator(
                    $currentResults,
                    $mergedStores->count(),
                    $perPage,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            }
        } else {
            $stores = $storeQuery->paginate(10);
        }

        $totalStores = $stores->count();

        // Si no hay resultados, cambiar el ámbito de búsqueda
        if ($stores->isEmpty()) {
            $locationStores = 'municipality';
            $storeQuery = Store::where('status', true)
                ->where('municipalities_id', $municipalityId)
                ->where('type_stores_id', $request->type)
                ->with('municipality');

            $storeQuery = $applyFilters($storeQuery);

            if ($request->type == env('TIPO_TALLER_ID') && $request->categoryId == env('TALLER_MECANICO')) {
                $storeQuery2 = Store::where('status', true)
                    ->where('municipalities_id', $municipalityId)
                    ->where('services', true)
                    ->with('municipality');

                if ($sectorId !== 'Todos') {
                    $storeQuery2->where('sectors_id', $sectorId);
                }

                // Obtener resultados individuales
                $stores1 = $storeQuery->get();
                $stores2 = $storeQuery2->get();

                // Combinar resultados
                $mergedStores = $stores1->merge($stores2);

                // Implementar paginación manual
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $currentResults = $mergedStores->slice(($currentPage - 1) * $perPage, $perPage);

                $stores = new LengthAwarePaginator(
                    $currentResults,
                    $mergedStores->count(),
                    $perPage,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            } else {
                $stores = $storeQuery->paginate(10);
            }

            $totalStores = $stores->count();

            if ($stores->isEmpty()) {
                $locationStores = 'state';
                $municipalities = Municipality::where('states_id', $stateId)->pluck('id');

                $storeQuery = Store::where('status', true)
                    ->whereIn('municipalities_id', $municipalities)
                    ->where('type_stores_id', $request->type)
                    ->with('municipality');

                $storeQuery = $applyFilters($storeQuery);

                if ($request->type == env('TIPO_TALLER_ID') && $request->categoryId == env('TALLER_MECANICO')) {
                    $storeQuery2 = Store::where('status', true)
                        ->where('municipalities_id', $municipalityId)
                        ->where('services', true)
                        ->with('municipality');

                    if ($sectorId !== 'Todos') {
                        $storeQuery2->where('sectors_id', $sectorId);
                    }

                    // Obtener resultados individuales
                    $stores1 = $storeQuery->get();
                    $stores2 = $storeQuery2->get();

                    // Combinar resultados
                    $mergedStores = $stores1->merge($stores2);

                    // Implementar paginación manual
                    $currentPage = LengthAwarePaginator::resolveCurrentPage();
                    $perPage = 10;
                    $currentResults = $mergedStores->slice(($currentPage - 1) * $perPage, $perPage);

                    $stores = new LengthAwarePaginator(
                        $currentResults,
                        $mergedStores->count(),
                        $perPage,
                        $currentPage,
                        ['path' => LengthAwarePaginator::resolveCurrentPath()]
                    );
                } else {
                    $stores = $storeQuery->paginate(10);
                }

                $totalStores = $stores->count();

                if ($stores->isEmpty()) {
                    $locationStores = 'country';
                    $municipalities = Municipality::pluck('id');

                    $storeQuery = Store::where('status', true)
                        ->whereIn('municipalities_id', $municipalities)
                        ->where('type_stores_id', $request->type)
                        ->with('municipality');

                    $storeQuery = $applyFilters($storeQuery);

                    if ($request->type == env('TIPO_TALLER_ID') && $request->categoryId == env('TALLER_MECANICO')) {
                        $storeQuery2 = Store::where('status', true)
                            ->where('municipalities_id', $municipalityId)
                            ->where('services', true)
                            ->with('municipality');

                        if ($sectorId !== 'Todos') {
                            $storeQuery2->where('sectors_id', $sectorId);
                        }

                        // Obtener resultados individuales
                        $stores1 = $storeQuery->get();
                        $stores2 = $storeQuery2->get();

                        // Combinar resultados
                        $mergedStores = $stores1->merge($stores2);

                        // Implementar paginación manual
                        $currentPage = LengthAwarePaginator::resolveCurrentPage();
                        $perPage = 10;
                        $currentResults = $mergedStores->slice(($currentPage - 1) * $perPage, $perPage);

                        $stores = new LengthAwarePaginator(
                            $currentResults,
                            $mergedStores->count(),
                            $perPage,
                            $currentPage,
                            ['path' => LengthAwarePaginator::resolveCurrentPath()]
                        );
                    } else {
                        $stores = $storeQuery->paginate(10);
                    }

                    $totalStores = $stores->count();
                }
            }
        }

        // Desencriptar direcciones
        foreach ($stores as $store) {
            $store->address = Crypt::decrypt($store->address);
        }

        // Retornar respuesta JSON
        return response()->json([
            'stores' => $stores,
            'locationStores' => $locationStores,
            'totalStores' => $totalStores,
            'categoryId' => $request->categoryId
        ], 200);
    }

    public function getProductsSearch($query)
    {
        $products = Product::whereHas('stores', function ($q) {
            $q->where('status', 1);  // Filtra tiendas activas
        })
            ->whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$query])  // Búsqueda de texto completo
            ->limit(5)
            ->get();

        return response()->json($products);
    }

    public function getProductsSearch2($query, $id)
    {
        // Usar full-text search en lugar de 'LIKE' para buscar en el campo 'name'
        $products = Product::whereHas('stores', function ($q) use ($id) {
            $q->where('stores.id', $id);   // Filtrar por tienda
        })
            ->whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$query])  // Búsqueda de texto completo
            ->limit(5)
            ->get();

        return response()->json($products);
    }

    public function getProductsSearch3($query)
    {
        $products = Product::whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$query])
            ->limit(5)
            ->get();

        return response()->json($products);
    }

    public function getProductsSearch4($query, $page = 1)
    {
        $perPage = 20; // Número de productos por página
        $offset = ($page - 1) * $perPage;

        $products = Product::whereRaw("MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)", [$query])
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // Mapear los productos para incluir solo la descripción de la marca en `brand`
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'reference' => $product->reference,
                'code' => $product->code,
                'reference' => $product->reference,
                'detail' => $product->detail,
                'image' => $product->image,
                'brand' => $product->brand ? $product->brand->description : null, // Incluye la descripción de la marca
            ];
        });

        return response()->json($products);
    }

    public function getChats($userId)
    {
        $final_array = [];

        // Obtener conversaciones del usuario y las asociadas a su tienda (si tiene)
        $conversations = Conversation::where('users_id', $userId)->orWhere('stores_id', $userId)->with(['user', 'store', 'messages'])->get();

        // Ordenar las conversaciones por la fecha del mensaje más reciente
        $conversations = $conversations->sortByDesc(function ($conversation) {
            return optional($conversation->messages->last())->created_at;
        });

        $my_user = User::find($userId);

        // Recorrer las conversaciones y agregar datos al array final
        foreach ($conversations as $key => $conversation) {
            $user = User::find($conversation->users_id);
            $user2 = User::find($conversation->stores_id);
            $lastMessage = $conversation->messages->last(); // Obtener el último mensaje

            // Verificar si se encontraron tanto el usuario como la tienda y si hay mensajes
            if ($user && $user2 && $lastMessage) {
                // Determinar el usuario en función del ID actual
                if ($my_user->id != $user->id) {
                    $user = $user;
                } else {
                    $user = $user2;
                }

                // Establecer el nombre y la imagen del usuario o tienda
                if ($user->store) {
                    $final_array[$key]['user_name'] = $user->store->name;
                    $final_array[$key]['user_img'] = $user->store->image;
                } else {
                    $final_array[$key]['user_name'] = $user->name;
                    $final_array[$key]['user_img'] = $user->image;
                }

                // Verificar si la imagen es nula o vacía, asignar un avatar por defecto
                if (empty($final_array[$key]['user_img'])) {
                    $letter = strtoupper($final_array[$key]['user_name'][0]);
                    $final_array[$key]['user_img'] = 'https://ui-avatars.com/api/?name=' . $letter . '&color=7F9CF5&background=EBF4FF';
                }

                // Desencriptar el contenido del último mensaje y otros datos relacionados
                try {
                    $final_array[$key]['last_message'] = Crypt::decryptString($lastMessage->content);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $final_array[$key]['last_message'] = 'Mensaje no disponible';
                }

                $final_array[$key]['last_message_time'] = $lastMessage->created_at;
                $final_array[$key]['last_message_status'] = $lastMessage->status;

                try {
                    $final_array[$key]['last_message_from'] = Crypt::decrypt($lastMessage->from);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $final_array[$key]['last_message_from'] = 'Remitente no disponible';
                }

                $final_array[$key]['id'] = $conversation->id;
            }
        }

        return response()->json(array_values($final_array));
    }


    public function getInfoHome($userId, $municipalityId = null) // Permitir null como valor por defecto
    {
        $date = Carbon::now();

        // Obtener la ciudad del usuario
        $userCityId = $municipalityId;

        // Obtener las tiendas en promoción, primero las que están en la misma ciudad
        $storesQuery = Store::where('status', true)
            ->whereHas('promotions', function ($query) use ($date) {
                $query->where('status', true)
                    ->where('date_init', '<=', $date)
                    ->where('date_end', '>=', $date);
            })
            ->with('municipality');

        // Si el usuario tiene una ciudad, priorizar las tiendas de su misma ciudad
        if ($userCityId !== null) { // Solo aplicar si no es null
            $storesQuery = $storesQuery->orderByRaw("IF(municipalities_id = ?, 0, 1)", [$userCityId]);
        }

        $stores = $storesQuery->take(6)->get();

        $stores2 = collect();
        $stores3 = collect();

        // Si el usuario no está autenticado
        if (!Auth::check()) {
            $stores2 = SearchUser::join('stores', 'search_users.stores_id', '=', 'stores.id')
                ->join('municipalities', 'stores.municipalities_id', '=', 'municipalities.id')
                ->with(['store', 'store.municipality'])
                ->when($userCityId, function ($query) use ($userCityId) {
                    return $query->orderByRaw("IF(stores.municipalities_id = ?, 0, 1)", [$userCityId]);
                })
                ->limit(9)
                ->get();

            $services = Store::with('municipality')->where('type_stores_id', '!=', env('TIPO_TIENDA_ID'))
                ->inRandomOrder()
                ->limit(9)
                ->get();

            $stores3 = SearchUser::join('stores', 'search_users.stores_id', '=', 'stores.id')
                ->join('municipalities', 'stores.municipalities_id', '=', 'municipalities.id')
                ->with(['product', 'store'])
                ->when($userCityId, function ($query) use ($userCityId) {
                    return $query->orderByRaw("IF(stores.municipalities_id = ?, 0, 1)", [$userCityId]);
                })
                ->limit(9)
                ->get();
        } else {
            $userId = Auth::id();

            $stores2 = SearchUser::where('users_id', $userId)
                ->join('stores', 'search_users.stores_id', '=', 'stores.id')
                ->join('municipalities', 'stores.municipalities_id', '=', 'municipalities.id')
                ->with(['store', 'store.municipality'])
                ->when($userCityId, function ($query) use ($userCityId) {
                    return $query->orderByRaw("IF(stores.municipalities_id = ?, 0, 1)", [$userCityId]);
                })
                ->limit(9)
                ->get();

            $services = Store::with('municipality')->where('type_stores_id', '!=', env('TIPO_TIENDA_ID'))
                ->inRandomOrder()
                ->limit(9)
                ->get();

            $stores3 = SearchUser::where('users_id', $userId)
                ->join('stores', 'search_users.stores_id', '=', 'stores.id')
                ->join('municipalities', 'stores.municipalities_id', '=', 'municipalities.id')
                ->with(['product', 'store'])
                ->limit(9)
                ->get();
        }

        $array_stores = [];
        $array_stores_final = [];
        $array_products = [];
        $array_products_final = [];

        try {
            foreach ($stores2 as $store) {
                if ($store->store) {
                    $store_id = $store->store->id;
                    if (!in_array($store_id, $array_stores)) {
                        // Desencriptar datos del store
                        $store->store->address = Crypt::decrypt($store->store->address);

                        $array_stores[] = $store_id;
                        $array_stores_final[] = $store->store;
                    }
                }
            }

            foreach ($services as $service) {
                $service->address = Crypt::decrypt($service->address);
            }

            foreach ($stores3->reverse() as $product) {
                if ($product->product) {
                    $product_id = $product->product->id;
                    if (!in_array($product_id, $array_products)) {
                        $array_products[] = $product_id;
                        $array_products_final[] = $product->product;
                    }
                }
            }

            // Desencriptar datos en $stores
            foreach ($stores as $store) {
                if ($store) {
                    try {
                        $store->address = Crypt::decrypt($store->address);
                    } catch (\Exception $e) {
                        $store->email = null;
                        $store->address = null;
                        $store->phone = null;
                    }
                }
            }

            $publicities = Publicity::where('date_end', '>', $date)
                ->where('status', true)
                ->inRandomOrder()
                ->take(30)
                ->get();

            $videos = Information::all();

            return response()->json([
                'videos' => $videos,
                'publicities' => $publicities,
                'stores' => $stores,
                'lastStores' => $array_stores_final,
                'lastSearch' => $array_products_final,
                'servicesMoreSearch' => $services,
                'userCityId' => $userCityId
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function getAllStoresPromotion(Request $request)
    {
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) {
            $query->where('status', true)->where('date_init', '<=', Carbon::now())->where('date_end', '>=', Carbon::now());
        })->with('municipality')->paginate(10);

        foreach ($stores as $store) {
            $store->address = Crypt::decrypt($store->address);
        }

        return response()->json($stores);
    }

    public function registerStorePost(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'municipalities_id' => 'required',
            'sectors_id' => 'required',
            'name' => 'required|string|max:100|unique:stores',
            'description' => 'required|string|max:500',
            'email' => 'required|email|unique:stores',
            'address' => 'required|max:255',
            'rif' => 'required|max:255|unique:stores', // Agregamos la regla 'unique'
            'phone' => ['required', 'regex:/^(0412|0414|0416|0424|0426)\d{7}$/']
        ], [
            // Mensajes personalizados
            'rif.required' => 'El campo RIF es obligatorio.',
            'rif.unique' => 'Este RIF ya está registrado en la base de datos.',
            'name.unique' => 'El nombre de la tienda ya está en uso.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'phone.regex' => 'El formato del teléfono no es válido. Debe comenzar con 0412, 0414, 0416, 0424 o 0426.',
        ]);

        $type_store = TypeStore::where('description', $request->typeStore)->first();

        //Registro de la tienda
        $store = new Store();
        $store->type_stores_id = $type_store->id;
        $store->users_id = $request->users_id;
        $store->municipalities_id = $request->municipalities_id;
        $store->sectors_id = $request->sectors_id;
        $store->name = $request->name;
        $store->description = $request->description;
        $store->email = Crypt::encrypt($request->email);
        $store->address = Crypt::encrypt($request->address);
        $store->link = str_replace(' ', '-', $request->name);
        $store->status = false;
        $store->RIF = Crypt::encrypt($request->rif);
        $store->phone = Crypt::encrypt($request->phone);
        $store->score_store = 0;
        $store->created_at = Carbon::now();
        $store->categories_stores_id = $request->categories_stores_id;

        if ($request->typeStore == 'Grua') {
            $store->tipo = $request->tipo;
            $store->dimensiones = $request->dimensiones;
            $store->capacidad = $request->capacidad;
        }

        if (isset($request->services)) {
            $store->services = $request->services;
        }

        $store->save();

        // Notificar a los administradores
        $administrators = User::whereHas('profile', function ($query) {
            $query->where('description', 'Administrador');
        })->get();

        foreach ($administrators as $admin) {
            $admin->email = Crypt::decrypt($admin->email);
            Mail::to($admin->email)->send(new StoreRegisteredNotification($store, $admin));
        }

        //Registro del plan contratado
        $type_plan = Plan::where('description', 'Basico')->first();
        $plan = new PlanContracting();
        $plan->plans_id = $type_plan->id;
        $plan->stores_id = $store->id;
        $plan->date_init = Carbon::now();
        $plan->date_end = Carbon::now()->addDay(intval($type_plan->days));
        $plan->status = true;
        $plan->created_at = Carbon::now();
        $plan->save();

        // Eliminar todas las conversaciones y sus mensajes asociados para el usuario dado
        //Conversation::where('users_id', $request->users_id)->delete();

        //Cambiar perfil de usuario
        $user = User::find($request->users_id);
        if($request->typeStore == 'Tienda'){
            $user->profiles_id = 2;
        } else if($request->typeStore == 'Taller'){
            $user->profiles_id = 4;
        } else if($request->typeStore == 'Cauchera'){
            $user->profiles_id = 6;
        }else{
            $user->profiles_id = 5;
        }

        $user->save();

        $store->email = $request->email;
        $store->address = $request->address;
        $store->RIF = $request->rif;
        $store->phone = $request->phone;

        $user->email = $user->email ? Crypt::decrypt($user->email) : null;
        $user->address = $user->address ? Crypt::decrypt($user->address) : null;
        $user->phone = $user->phone ? Crypt::decrypt($user->phone) : null;

        //Devolvemos la tienda
        return response()->json(['store' => $store, 'user' => $user], 200);
    }

    public function typePublicities($userId)
    {
        $type_publicities = TypePublicity::all();
        $store = Store::where('users_id', $userId)->first();
        $products = $store->products;
        return response()->json(['type_publicities' => $type_publicities, 'products' => $products], 200);
    }

    public function savePublicity(Request $request)
    {
        if ($request->hasFile('selectedImage')) {
            $store = Store::where('users_id', $request->user_id)->first();
            $type_publicity = TypePublicity::find($request->type);

            $publicity = new Publicity();
            $publicity->stores_id = $store->id;
            $publicity->type_publicities_id = $request->type;
            $publicity->title = $request->title;
            $publicity->image = '';
            $publicity->description = $request->description;
            $publicity->link = str_replace(' ', '-', $store->name);
            $publicity->status = false;
            $publicity->date_init = Carbon::now();
            $publicity->date_end = Carbon::now()->addDays($type_publicity->amount_days);
            $publicity->created_at = Carbon::now();
            $publicity->save();

            $route_image = $request->file('selectedImage')->store('public/images-publicity/' . $publicity->id);
            $url = Storage::url($route_image);

            $publicity->image = $url;
            $publicity->save();

            $users = User::whereHas('profile', function ($query) {
                $query->where('description', 'Administrador');
            })->get();

            $type = 'publicidad';

            foreach ($users as $user) {
                $user->email = Crypt::decrypt($user->email);
                $user->notify(new NotifyAdmin($user, $store, $type));
            }

            return response()->json(['success' => 'Publicity created successfully'], 200);
        } else {
            return response()->json(['error' => 'No se ha recibido ninguna imagen'], 400);
        }
    }

    public function savePromotion(Request $request)
    {
        $store = Store::where('users_id', $request->user_id)->first();
        $product_store = ProductStore::where('stores_id', $store->id)->where('products_id', $request->products_id)->first();

        $promotion = new Promotion();
        $promotion->products_id = $request->products_id;
        $promotion->stores_id = $store->id;

        // Convertir el formato de fecha
        $promotion->date_init = Carbon::parse($request->date_init)->format('Y-m-d H:i:s');
        $promotion->date_end = Carbon::parse($request->date_end)->format('Y-m-d H:i:s');

        $promotion->price = $product_store->price - ($product_store->price * ($request->percent_promotion * 0.01));
        $promotion->status = false;
        $promotion->description = $request->description;
        $promotion->created_at = Carbon::now();
        $promotion->save();

        $users = User::whereHas('profile', function ($query) {
            $query->where('description', 'Administrador');
        })->get();

        $type = 'promoción';

        foreach ($users as $user) {
            $user->email = Crypt::decrypt($user->email);
            $user->notify(new NotifyAdmin($user, $store, $type));
        }

        return response()->json(['success' => 'Promotion created successfully'], 200);
    }

    public function saveWork(Request $request)
    {
        if ($request->hasFile('selectedImage')) {
            $store = Store::find($request->store_id);

            $work = new Work();
            $work->stores_id = $store->id;
            $work->image = '';
            $work->title = $request->title;
            $work->description = $request->description;
            $work->status = false;
            $work->created_at = Carbon::now();
            $work->save();

            $route_image = $request->file('selectedImage')->store('public/images-work/' . $store->id);
            $url = Storage::url($route_image);

            $work->image = $url;
            $work->save();

            return response()->json(['success' => 'Work created successfully'], 200);
        } else {
            return response()->json(['error' => 'No se ha recibido ninguna imagen'], 400);
        }
    }

    public function getMunicipalities()
    {
        $municipalities = Municipality::all();
        $type_stores = TypeStore::all();
        return response()->json(['municipalities' => $municipalities, 'type_stores' => $type_stores], 200);
    }

    public function sectors(Request $request)
    {
        $sectors = Sector::where('municipalities_id', $request->municipality)->get();
        return response()->json($sectors, 200);
    }

    public function municipalities(Request $request)
    {
        $municipalities = Municipality::where('states_id', $request->state)->get();
        return response()->json($municipalities, 200);
    }

    public function sendSignalAux(Request $request)
    {
        // Encontrar usuario que envía el auxilio vial
        $user = User::findOrFail($request->userId);
        $name = $user->name;

        // Verificar si el usuario ya tiene una señal activa del mismo tipo de tienda
        $existingSignal = SignalAux::where('users_id', $user->id)
            ->where('read', false)
            ->whereHas('store', function ($query) use ($request) {
                $query->where('type_stores_id', $request->type);
            })
            ->exists();

        if ($existingSignal) {
            if ($request->type == env('TIPO_TALLER_ID')) {
                $existingSignal = SignalAux::where('users_id', $user->id)
                    ->where('read', false)
                    ->whereHas('store', function ($query) use ($request) {
                        $query->where('categories_stores_id', env('TIPO_REPUESTOS'));
                        $query->where('services', true);
                    })
                    ->exists();

                if ($existingSignal) {
                    return response()->json(['error' => 'Ya tienes una señal activa de este tipo.'], 400);
                }
            }
            return response()->json(['error' => 'Ya tienes una señal activa de este tipo.'], 400);
        }

        // Encontrar tiendas en la ciudad, del tipo y activas
        $storesQuery = Store::where('type_stores_id', $request->type)
            ->where('status', true)
            ->where('municipalities_id', $request->municipality);

        /*if ($request->sector !== 'Todos') {
            $storesQuery->where('sectors_id', $request->sector);
        }*/

        if ($request->categoryId != 0 && $request->categoryId != '0' && $request->categoryId != null) {
            if($request->type == env('TIPO_TALLER_ID') && $request->selectedServiceType == 'moto'){
                $storesQuery->where('categories_stores_id', env('TALLER_MOTOS'));
            }else{
                $storesQuery->where('categories_stores_id', $request->categoryId);
            }
        }

        $stores = $storesQuery->get();

        if ($request->type == env('TIPO_TALLER_ID')) {
            $storesQuery2 = Store::where('services', true)
                ->where('categories_stores_id', env('TIPO_REPUESTOS'))
                ->where('status', true)
                ->where('municipalities_id', $request->municipality);

            if ($request->sector !== 'Todos') {
                $storesQuery2->where('sectors_id', $request->sector);
            }

            $stores2 = $storesQuery2->get();

            // Combinar tiendas si stores2 tiene contenido
            if ($stores2->isNotEmpty()) {
                $stores = $stores->merge($stores2);
            }
        }

        $storesSendSignalAux = [];

        $type = '';

        $processImage = false;

        $count = 0;

        // Enviar señal y notificación a cada tienda sin una señal activa no leída
        foreach ($stores as $store) {
            // Verificar que la tienda no esté asociada al usuario que envía la señal
            if ($store->user->id === $user->id) {
                continue; // Saltar esta tienda si pertenece al usuario
            }

            $storeHasActiveSignal = SignalAux::where('stores_id', $store->user->id)
                ->where('status', true)
                ->where('read', false)
                ->exists();

            if (!$storeHasActiveSignal) {
                $sector = ($request->sector !== 'Todos')
                    ? optional(Sector::find($request->sector))->description ?? ''
                    : '';

                if ($sector != '') {
                    $detail_signal = 'Me encuentro en: ' . $sector . ', ' . $request->description;
                } else {
                    $detail_signal = $request->description;
                }

                if ($request->hasFile('image') && !($processImage)) {
                    // Obtener el archivo
                    $image = $request->file('image');

                    // Generar un nombre único para la imagen
                    $imagePath = $image->store('public/images-signals-aux');  // 'public/images' es el directorio donde se guarda la imagen

                    // Si necesitas la URL completa para acceder a la imagen
                    $imageUrl = Storage::url($imagePath);
                }

                $processImage = true;

                SignalAux::create([
                    'users_id' => $user->id,
                    'stores_id' => $store->user->id,
                    'detail' => $detail_signal,
                    'status' => false,  // Marcamos como abierta (esperando)
                    'status2' => false,
                    'read' => false,
                    'created_at' => now(),
                    'image' => $imageUrl ?? null,  // Guardar la URL de la imagen si existe
                ]);

                $storesSendSignalAux[] = $store;

                // Enviar notificación via Firebase si el token es válido
                $token = $store->user->token;

                if (!empty($token) && strlen($token) > 10) {
                    try {
                        $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
                        $messaging = $firebase->createMessaging();

                        $message = CloudMessage::fromArray([
                            'token' => $token,
                            'notification' => [
                                'title' => $name,
                                'body' => 'Requiero auxilio vial',
                            ],
                            'data' => [
                                'click_action' => 'OPEN_URL',
                                'url' => '/signals-aux',
                            ],
                            'android' => [
                                'priority' => 'high',
                            ],
                        ]);

                        $messaging->send($message);
                    } catch (\Throwable $e) {
                        Log::warning('Error al enviar notificación: ' . $e->getMessage() . ' | User: ' . $store->user);
                    }
                } else {
                    Log::warning('Token inválido o vacío para el usuario: ' . $store->user->id);
                }

                $count++;

                event(new NewMessage2([], $store->users_id));
            }
        }

        if ($request->type == env('TIPO_TALLER_ID')) {
            $type = 'Taller';
        } else if ($request->type == env('TIPO_GRUA_ID')) {
            $type = 'Grua';
        } else {
            $type = 'Cauchera';
        }

        return response()->json(['stores' => $storesSendSignalAux, 'categoryId' => $request->categoryId, 'typeStore' => $type, 'count' => $count], 200);
    }


    public function getSignalsAux(Request $request)
    {
        $user_id = $request->userId;

        // Obtener señales recibidas
        $signals_aux = SignalAux::where('users_id', $user_id)->orderBy('id', 'desc')->get();
        $array_data = [];

        foreach ($signals_aux as $signal) {
            $user = User::find($signal->stores_id);
            $typeStore = $user->store->type_stores_id; // Obtener tipo de tienda
            $typeStoreString = '';

            if ($typeStore == env('TIPO_TALLER_ID')) {
                $typeStoreString = 'taller';
            } else if ($typeStore == env('TIPO_GRUA_ID')) {
                $typeStoreString = 'grúa';
            } else if ($typeStore == env('TIPO_CAUCHERA_ID')) {
                $typeStoreString = 'cauchera';
            }

            // Si el status es false y aún no hemos agregado una señal de este tipo de tienda, se agrupa
            if ($signal->status == false && !isset($array_data[$typeStore])) {
                $array_data[$typeStore] = [
                    'id' => $signal->id,
                    'name' => $user->store->name,
                    'image' => $user->store->image ?: 'https://ui-avatars.com/api/?name=' . strtoupper($user->store->name[0]) . '&amp;color=7F9CF5&amp;background=EBF4FF',
                    'created_at' => $signal->created_at,
                    'detail' => $signal->detail,
                    'status' => $signal->status,
                    'status2' => $signal->status2,
                    'read' => $signal->read,
                    'idStore' => $user->store->id,
                    'typeStore' => $typeStore,
                    'typeStoreString' => $typeStoreString
                ];
            } elseif ($signal->status != false) {
                // Aquí se agregan todas las señales que no cumplen la condición de agrupar por tipo de tienda
                $array_data[] = [
                    'id' => $signal->id,
                    'name' => $user->store->name,
                    'image' => $user->store->image ?: 'https://ui-avatars.com/api/?name=' . strtoupper($user->store->name[0]) . '&amp;color=7F9CF5&amp;background=EBF4FF',
                    'created_at' => $signal->created_at,
                    'detail' => $signal->detail,
                    'status' => $signal->status,
                    'status2' => $signal->status2,
                    'read' => $signal->read,
                    'idStore' => $user->store->id,
                    'typeStore' => $typeStore,
                    'typeStoreString' => $typeStoreString
                ];
            }
        }


        // Obtener señales enviadas
        $signals_aux2 = SignalAux::where('stores_id', $user_id)->orderBy('id', 'desc')->get();
        $array_data2 = [];

        foreach ($signals_aux2 as $key => $signal) {
            $user = User::find($signal->users_id);
            if ($user->store) {
                $array_data2[$key]['id'] = $signal->id;
                $array_data2[$key]['name'] = $user->store->name;
                if ($user->store->image == null || $user->store->image == '') {
                    $letter = strtoupper($user->store->name[0]);
                    $array_data2[$key]['image'] = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
                } else {
                    $array_data2[$key]['image'] = $user->store->image;
                }
                $array_data2[$key]['created_at'] = $signal->created_at;
                $array_data2[$key]['detail'] = $signal->detail;
                $array_data2[$key]['status'] = $signal->status;
                $array_data2[$key]['status2'] = $signal->status2;
                $array_data2[$key]['read'] = $signal->read;
            } else {
                $array_data2[$key]['id'] = $signal->id;
                $array_data2[$key]['name'] = $user->name;
                if ($user->image == null || $user->image == '') {
                    $letter = strtoupper($user->name[0]);
                    $array_data2[$key]['image'] = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
                } else {
                    $array_data2[$key]['image'] = $user->image;
                }
                $array_data2[$key]['created_at'] = $signal->created_at;
                $array_data2[$key]['detail'] = $signal->detail;
                $array_data2[$key]['status'] = $signal->status;
                $array_data2[$key]['status2'] = $signal->status2;
                $array_data2[$key]['read'] = $signal->read;
            }
        }

        // Convertir los arrays agrupados a un array numérico
        $array_data = array_values($array_data);
        $array_data2 = array_values($array_data2);

        $obscene_words = ObsceneWord::all();

        return response()->json(['array_send' => $array_data, 'array_recive' => $array_data2, 'obscene_words' => $obscene_words], 200);
    }

    public function changeStatusSignalsAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        if ($signal == null) {
            return response()->json(['id' => 0], 200);
        }
        $signal->confirmation = Carbon::now();
        $signal->status = true;
        $signal->save();

        // Eliminar todas las filas relacionadas con el usuario excepto la actual
        $signals = SignalAux::where('users_id', $signal->users_id)
            ->where('id', '!=', $signal->id)
            ->where('status', '!=', true)
            ->get();

        // Busca si existe una conversación entre el usuario y la tienda
        $conversation = Conversation::where('users_id', $signal->users_id)->where('stores_id', $signal->stores_id)->first();

        if ($conversation == null) {
            $conversation = Conversation::where('users_id', $signal->stores_id)->where('stores_id', $signal->users_id)->first();
        }

        // Si no existe conversación y los IDs no coinciden, crea una nueva
        if ($conversation == null) {
            $conversation = new Conversation();
            $conversation->users_id = $signal->users_id;
            $conversation->stores_id = $signal->stores_id;
            $conversation->created_at = Carbon::now();
            $conversation->save();
        }

        $user = User::find($signal->users_id);

        $message = new Message();
        $message->conversations_id = $conversation->id;
        $message->content = Crypt::encryptString($signal->detail);
        $message->from = $user->email;
        $message->status = false;
        $message->save();

        $array_data = [
            'status' => 'approve',
            'userId' =>  $signal->users_id
        ];

        event(new NewMessage2($array_data, $signal->users_id));

        foreach ($signals as $signal) {
            $userId = $signal->users_id;
            $signal->delete();
            event(new NewMessage2($array_data, $userId));
        }

        return response()->json(['id' => $conversation->id], 200);
    }

    public function removeSignalAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        if ($signal != null) {
            $signal->delete();
        }
        return response()->json('ok', 200);
    }

    public function removeSignalsAux(Request $request)
    {
        $userId = $request->userId;

        // Definir el tiempo límite de un minuto y medio (90 segundos) en el pasado
        $timeLimit = Carbon::now()->subSeconds(90);

        // Obtener las señales creadas dentro del último minuto y medio
        $signals = SignalAux::where('users_id', $userId)
            ->where('created_at', '>=', $timeLimit)
            ->get();  // Usar get() para obtener la colección de señales

        // Emitir el evento para cada señal antes de eliminarlas
        foreach ($signals as $signal) {
            event(new NewMessage2([], $signal->store->user->id));

            // Eliminar la imagen asociada si existe
            if (!empty($signal->image)) {
                // Convertir la URL completa en una ruta relativa al almacenamiento
                $imagePath = str_replace('/storage', 'public', $signal->image);

                // Verificar y eliminar la imagen
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }
        }

        // Luego de emitir los eventos, proceder a eliminar las señales
        SignalAux::where('users_id', $userId)
            ->where('created_at', '>=', $timeLimit)
            ->delete();

        return response()->json(['signals' => $signals], 200);
    }

    public function closeSignalsAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        $signal->read = true;
        $signal->save();
        event(new NewMessage2([], $signal->store->user->id));
        return response()->json('ok', 200);
    }

    public function qualitySignal(Request $request)
    {
        // Validación de los datos que llegan desde el frontend
        $validatedData = $request->validate([
            'stores_id' => 'required|integer',
            'users_id' => 'required|integer',
            'comment' => 'required|string',
            'rate' => 'required|integer',
        ]);

        // Guardar el comentario en la tabla 'comments_services'
        $comment = new Comment();
        $comment->stores_id = $validatedData['stores_id'];
        $comment->users_id = $validatedData['users_id'];
        $comment->comment = $validatedData['comment'];
        $comment->status = false;
        $comment->created_at = now(); // Fecha y hora actuales
        $comment->rate = $validatedData['rate'];

        // Guardar en la base de datos
        if ($comment->save()) {
            $signal = SignalAux::find($request->signalId);
            // Actualizar el estado de la señal
            if ($signal != false) {
                $signal->status2 = true;
                $signal->save();

                return response()->json([
                    'message' => 'Comentario guardado exitosamente',
                    'data' => $comment
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error al conseguir la señal'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Error al guardar el comentario'
            ], 500);
        }
    }

    public function updateComponent(Request $request)
    {
        $states = State::all();
        $municipalities = Municipality::where('states_id', $request->stateId)->get();
        $sectors = Sector::where('municipalities_id', $request->municipalityId)->get();
        return response()->json(['states' => $states, 'municipalities' => $municipalities, 'sectors' => $sectors]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower($request->email); // Convertir a minúsculas

        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user) {
            return response()->json(['error' => 'Usuario no registrado'], 422);
        }

        $user->email = $email;

        $user->notify(new ResetPasswordApi($request->token));

        return response()->json($user);
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'token' => 'required|string',
            'type' => 'required|string'
        ]);

        $email = strtolower($request->email); // Convertir a minúsculas

        if ($request->type == 'store') {
            $store = Store::all()->first(function ($store) use ($email) {
                try {
                    return Crypt::decrypt($store->email) === $email;
                } catch (\Exception $e) {
                    return false;
                }
            });

            if ($store) {
                return response()->json(['error' => 'Este correo ya existe'], 422);
            }
        } else {
            $user = User::all()->first(function ($user) use ($email) {
                try {
                    return Crypt::decrypt($user->email) === $email;
                } catch (\Exception $e) {
                    return false;
                }
            });

            if ($user) {
                return response()->json(['error' => 'Este correo ya existe'], 422);
            }
        }

        // Enviar el correo
        Mail::to($email)->send(new VerificationEmail($request->token));

        return response()->json(['message' => 'Correo de verificación enviado con éxito.'], 200);
    }

    public function recoveryAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower($request->email); // Convertir a minúsculas

        $user = User::all()->first(function ($user) use ($email) {
            try {
                return Crypt::decrypt($user->email) === $email;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user) {
            return response()->json(['error' => 'Usuario no registrado'], 422);
        }

        $user->email = $email;

        $user->notify(new RecoveryAccount($request->token));

        if ($user->store != null) {
            $user->store = $user->store->id;
        }

        $user->address = $user->address ? Crypt::decrypt($user->address) : null;
        $user->phone = $user->phone ? Crypt::decrypt($user->phone) : null;

        return response()->json($user);
    }

    public function replaceToken(Request $request)
    {
        $user = User::find($request->userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no registrado'], 422);
        }

        // Actualizar token de FCM si es necesario
        if ($userWithToken = User::where('token', $request->token)->first()) {
            $userWithToken->update(['token' => null]);
        }

        $user->token = $request->token;
        $user->save();

        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);
        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json($user);
    }

    public function getData(Request $request)
    {
        $name_label = urldecode(str_replace("_", " ", $request->label));
        $name_table = Table::where('label', $name_label)->value('name');

        if (!$name_table) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        $attributes = Schema::getColumnListing($name_table);
        $data = DB::table($name_table)->get();

        if ($name_label == 'Tiendas') {
            foreach ($data as $key) {
                $key->email = $key->email ? Crypt::decrypt($key->email) : null;
                $key->address = $key->address ? Crypt::decrypt($key->address) : null;
                $key->phone = $key->phone ? Crypt::decrypt($key->phone) : null;
                $key->RIF = $key->RIF ? Crypt::decrypt($key->RIF) : null;
            }
        }

        if ($name_label == 'Usuarios') {
            foreach ($data as $key) {
                $key->email = $key->email ? Crypt::decrypt($key->email) : null;
                $key->address = $key->address ? Crypt::decrypt($key->address) : null;
                $key->phone = $key->phone ? Crypt::decrypt($key->phone) : null;
            }
        }

        $dataTable = DataTables::of($data);

        foreach ($attributes as $field) {
            if (str_ends_with($field, '_id') && !($name_label == 'Tiendas' && $field == 'states_id') && $field != 'current_team_id') {
                $relatedTable = str_replace('_id', '', $field);
                $dataTable->editColumn($field, function ($row) use ($relatedTable, $field) {
                    $related = DB::table($relatedTable)->find($row->$field);
                    return $related->name ?? $related->description ?? '';
                });
            }
        }

        $specialFields = [
            'status' => function ($row) {
                return $row->status == 0 ? 'Desactivado' : 'Activado';
            },
            'hour' => function ($row) {
                return date('H:i', strtotime($row->hour));
            },
            'email_verified_at' => function ($row) {
                return $row->email_verified_at == null ? 'Inactiva' : 'Activa';
            },
            'session_active' => function ($row) {
                return $row->session_active == 0 ? 'Inactiva' : 'Activa';
            },
            'sucursal' => function ($row) {
                return $row->sucursal == 0 ? 'Inactiva' : 'Activa';
            },
            'services' => function ($row) {
                return $row->services == 0 ? 'Inactiva' : 'Activa';
            }
        ];

        foreach ($specialFields as $field => $callback) {
            if (in_array($field, $attributes)) {
                $dataTable->editColumn($field, $callback);
            }
        }


        $dataTable->addColumn('actions', function ($row) use ($attributes, $name_label) {
            $arrayExtraFields = [];
            $fieldsStr = "";
            $valuesStr = "";
            $count = 0;

            foreach ($attributes as $field) {
                if ($field != 'created_at' && $field != 'updated_at' && $field != 'current_team_id') {
                    if ($count == 0) {
                        $fieldsStr .= $field;
                    } else {
                        $fieldsStr .= "|$field";
                    }
                    $count++;
                }

                if (str_contains($field, '_id')) {
                    $arrayExtraFields[] = $field;
                }
            }


            if ($name_label == 'Tiendas' || $name_label == 'Usuarios') {
                $fieldsStr .= "|states_id";
            }

            foreach ($attributes as $field) {
                if ($field != 'created_at' && $field != 'updated_at' && $field != 'current_team_id') {
                    $valuesStr .= ",'" . $row->$field . "'";
                }
            }

            if (isset($row->aditionalPictures)) {
                $valuesStr .= ",'images:";
                foreach ($row->aditionalPictures as $index => $image) {
                    if ($index == 0) {
                        $valuesStr .= $image->image;
                    } else {
                        $valuesStr .= "|$image->image";
                    }
                }
                $valuesStr .= "'";
            }

            if ($name_label == 'Tiendas' || $name_label == 'Usuarios') {
                $municipality = Municipality::find($row->municipalities_id);
                if ($municipality) {
                    $valuesStr .= ",'" . $municipality->states_id . "'";
                }
            }

            $editUserParams = "['$fieldsStr'$valuesStr]";

            return '<a onclick="editUser(' . $editUserParams . ')" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user" style="cursor: pointer">
                        <i class="fas fa-user-edit text-secondary"></i>
                    </a>
                    <a href="#" onclick="deleteUser(' . $row->id . ')" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete user">
                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                    </a>
                    <form action="/delete-register" id="form-delete-' . $row->id . '" method="POST">
                        <input type="hidden" name="_token" value="' . @csrf_token() . '">
                        <input type="hidden" name="id" value="' . $row->id . '">
                        <input type="hidden" name="label" value="' . $name_label . '">
                    </form>';
        });

        return $dataTable->rawColumns(['actions'])->make(true);
    }

    public function getData2(Request $request)
    {
        $data = Product::with(['brand', 'subcategory', 'model'])->select('products.*');

        return DataTables::of($data)
            ->addColumn('brand', function ($product) {
                return $product->brand->description;
            })
            ->addColumn('subcategory', function ($product) {
                return $product->subcategory->name;
            })
            ->addColumn('model', function ($product) {
                return $product->model->description;
            })
            ->addColumn('created_at', function ($product) {
                return $product->created_at->format('d-m-Y');
            })
            ->addColumn('actions', function ($row) {
                // Definir los atributos específicos para la tabla de productos
                $attributes = [
                    'id',
                    'name',
                    'description',
                    'code',
                    'image',
                    'reference',
                    'detail',
                    'categories_id',
                    'sub_categories_id',
                    'brands_id',
                    'models_id',
                    'cylinder_capacities_id',
                    'type_products_id',
                    'boxes_id',
                    'created_at',
                    'aditionalPictures', // Suponiendo que esto es una relación o atributo de imágenes adicionales
                ];

                // Construir el array de campos y valores para editar usuario
                $fieldsStr = implode('|', $attributes);
                $valuesStr = implode("','", array_map(function ($field) use ($row) {
                    if ($field == 'aditionalPictures') {
                        if (isset($row->aditionalPictures)) {
                            return 'images:' . implode('|', array_column($row->aditionalPictures->toArray(), 'image'));
                        } else {
                            return '';
                        }
                    }
                    if ($field == 'categories_id') {
                        $subcategory = SubCategory::find($row->sub_categories_id);
                        return $subcategory ? $subcategory->categories_id : '';
                    }
                    return $row->$field ?? '';
                }, $attributes));

                // Generar el enlace de editar usuario
                $editUserParams = "['$fieldsStr','$valuesStr']";

                return '<a onclick="editUser(' . $editUserParams . ')" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user" style="cursor: pointer">
                            <i class="fas fa-user-edit text-secondary"></i>
                        </a>
                        <a href="#" onclick="deleteUser(' . $row->id . ')" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete user">
                            <i class="cursor-pointer fas fa-trash text-secondary"></i>
                        </a>
                        <form action="/delete-register" id="form-delete-' . $row->id . '" method="POST">
                            <input type="hidden" name="_token" value="' . @csrf_token() . '">
                            <input type="hidden" name="id" value="' . $row->id . '">
                            <input type="hidden" name="label" value="Productos">
                        </form>';
            })->rawColumns(['actions'])
            ->make(true);
    }


    public function saveProduct(Request $request)
    {
        $product_store = ProductStore::where('products_id', $request->productId)->where('stores_id', $request->storeId)->first();
        if ($product_store != null) {
            return response()->json(['message' => 'exist'], 200);
        }

        $product_store = new ProductStore();
        $product_store->products_id = $request->productId;
        $product_store->stores_id = $request->storeId;
        $product_store->amount = $request->amount;
        $product_store->price = $request->price;
        $product_store->created_at = Carbon::now();

        $product_store->save();

        return response()->json(['product' => $product_store], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'products' => 'required|array',
            'store_id' => 'required|integer',
        ]);

        foreach ($data['products'] as $product) {
            ProductStore::updateOrCreate(
                [
                    'products' => $product['id'],
                    'stores_id' => $data['store_id']
                ],
                [
                    'amount' => $product['amount'],
                    'price' => $product['price']
                ]
            );
        }

        return response()->json(['message' => 'Products updated successfully']);
    }

    // Método para agregar o actualizar productos en una tienda
    public function addProductToStore(Request $request, $storeId)
    {
        // Validamos los datos recibidos
        $validated = $request->validate([
            'id' => 'required|exists:products,id',
            'amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
        ]);

        // Buscamos si el producto ya está asociado con la tienda
        $storeProduct = ProductStore::where('stores_id', $storeId)
            ->where('products_id', $validated['id'])
            ->first();

        if ($storeProduct) {
            // Si el producto ya está en la tienda, actualizamos cantidad y precio
            $storeProduct->update([
                'amount' => $storeProduct->amount + $validated['amount'],
                'price' => $validated['price'],
            ]);
        } else {
            // Si no está, lo agregamos
            ProductStore::create([
                'stores_id' => $storeId,
                'products_id' => $validated['id'],
                'amount' => $validated['amount'],
                'price' => $validated['price'],
            ]);
        }

        return response()->json([
            'message' => 'Producto agregado/actualizado exitosamente en la tienda.',
        ], 200);
    }

    public function updateProductStore(Request $request, Product $product)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'price' => 'required|numeric|min:0', // El precio debe ser un número mayor o igual a 0
            'amount' => 'required|integer|min:0', // La cantidad debe ser un entero mayor o igual a 0
        ]);

        $product_store = ProductStore::where('products_id', $request->productId)->where('stores_id', $request->storeId)->first();

        // Actualizar los datos del producto
        $product_store->update($validated);

        return response()->json(['message' => 'Producto actualizado correctamente.', 'product' => $product], 200);
    }

    public function detachProductStore(Request $request, Store $store, Product $product)
    {
        // Verificar si el producto está asociado a la tienda
        if (!$store->products()->where('products.id', $product->id)->exists()) {
            return response()->json(['error' => 'El producto no está asociado a esta tienda.'], 404);
        }

        // Desasociar el producto
        $store->products()->detach($product->id);

        return response()->json(['message' => 'Producto desasociado correctamente.'], 200);
    }

    // Método opcional para eliminar un producto de la tienda
    public function removeProductFromStore($storeId, $productId)
    {
        $storeProduct = ProductStore::where('store_id', $storeId)
            ->where('product_id', $productId)
            ->first();

        if ($storeProduct) {
            $storeProduct->delete();
            return response()->json(['message' => 'Producto eliminado de la tienda.'], 200);
        }

        return response()->json(['message' => 'Producto no encontrado en la tienda.'], 404);
    }

    public function getCommentaries(Request $request)
    {
        $store = Store::find($request->store_id);
        if (!$store) {
            return response()->json(['error' => 'Tienda no encontrada'], 404);
        }

        $commentaries = Comment::where('stores_id', $store->id)->orderBy('created_at', 'desc')->get();
        foreach ($commentaries as $index => $comment) {
            if ($comment->user->store) {
                $comment->user->image = $comment->user->store->image;
                $comment->user->name = $comment->user->store->name;
                $comment->store = $comment->user->store->id;
            } else {
                $comment->store = null;
            }
            $image = $comment->user->image;
            if (is_null($image) || $image == '') {
                $letter = strtoupper($comment->user->name[0]);
                $comment->user_img = 'https://ui-avatars.com/api/?name=' . $letter . '&color=7F9CF5&background=EBF4FF';
            } else {
                $comment->user_img = $image;
            }
            $comment->name = $comment->user->name;
        }

        return response()->json(['commentaries' => $commentaries], 200);
    }

    public function getCategoriesStore(Request $request)
    {
        $typeStoresId = $request->type_stores_id;
        $categories = CategoryStore::where('type_stores_id', $typeStoresId)->get();

        return response()->json(['categories' => $categories]);
    }

    public function getMunicipalities2(Request $request)
    {
        $stateId = $request->input('stateId');

        // Validar el ID del estado
        if (!$stateId) {
            return response()->json(['error' => 'El estado es requerido'], 400);
        }

        // Obtener los municipios
        $municipalities = Municipality::where('states_id', $stateId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($municipalities);
    }

    /**
     * Obtener sectores basado en el municipio seleccionado.
     */
    public function getSectors(Request $request)
    {
        $municipalityId = $request->input('municipalityId');

        // Validar el ID del municipio
        if (!$municipalityId) {
            return response()->json(['error' => 'El municipio es requerido'], 400);
        }

        // Obtener los sectores
        $sectors = Sector::where('municipalities_id', $municipalityId)
            ->orderBy('description', 'asc')
            ->get(['id', 'description']);

        return response()->json($sectors);
    }

    public function createSucursal()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $categories_stores = CategoryStore::where('type_stores_id', Auth::user()->store->type_stores_id)->get();
        $store = Auth::user()->store;
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states,
            'categories_stores' => $categories_stores,
            'store' => $store
        ];

        $store->RIF = Crypt::decrypt($store->RIF);

        return view('create-sucursal', $array_data);
    }

    public function renovationStore(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'comentary' => 'required|string',
            'image' => 'nullable|string',  // Se espera una URL de la imagen
        ]);

        if ($request->hasFile('file')) {
            $route_image = $request->file('file')->store('public/images-renovation');
            $url = Storage::url($route_image);
        } else {
            return response()->json(['message' => 'No se ha subido ninguna imagen'], 400);
        }

        // Crear un nuevo registro en la tabla renovations
        $renovation = new Renovation();
        $renovation->stores_id = Auth::user()->store->id;  // Suponiendo que el usuario tiene una tienda asociada
        $renovation->plans_id = $validated['plan_id'];
        $renovation->image = $url;
        $renovation->comentary = $validated['comentary'];
        $renovation->status = false;  // Estado inicial
        $renovation->created_at = Carbon::now();
        $renovation->save();

        return response()->json(['success' => true, 'message' => 'Renovación guardada correctamente.']);
    }

    public function getDataRegisterProduct(Request $request)
    {
        $store = Store::find($request->storeId);
        $subCategories = SubCategory::whereHas('category', function ($query) use ($store) {
            $query->where('name', $store->category->description);
        })->get();
        $brands = Brand::all();
        $cylinder_capacities = cylinderCapacity::all();
        $models = Modell::all();
        $boxes = Box::all();

        $cylinder_no_apply = cylinderCapacity::where('description', 'No aplica')->first();
        $model_no_apply = Modell::where('description', 'No aplica')->first();
        $box_no_apply = Box::where('description', 'No aplica')->first();

        $products = Product::whereHas('subcategory', function ($query) use ($store) {
            $query->whereHas('category', function ($query) use ($store) {
                $query->where('name', $store->category->description);
            });
        })->take(12)->get();

        $response = [
            'cylinder_capacities' => $cylinder_capacities,
            'models' => $models,
            'boxes' => $boxes,
            'subCategories' => $subCategories,
            'brands' => $brands,
            'cylinder_no_apply' => $cylinder_no_apply,
            'model_no_apply' => $model_no_apply,
            'box_no_apply' => $box_no_apply,
            'category_store' => $store->category->description,
            'products' => $products
        ];

        return response()->json($response);
    }

    public function createNewProduct(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',  // Nombre del producto
            'category' => 'required',  // Asegúrate de que la categoría exista en la base de datos
            'brand' => 'required|exists:brands,id',  // Asegúrate de que la marca exista en la base de datos
            'description' => 'nullable|string',  // Descripción opcional
            'code' => 'nullable|string|max:255',  // Código del producto
            'detail' => 'nullable|string',  // Detalles del producto
            'amount' => 'required|integer|min:1',  // Cantidad, debe ser un número entero positivo
            'price' => 'required|numeric|min:0',  // Precio, debe ser un número positivo
            'storeId' => 'required|exists:stores,id', // El ID de la tienda también debe existir en la base de datos
        ]);

        // Cargar datos necesarios de la base de datos en un solo paso
        $cylinder_no_apply = cylinderCapacity::firstWhere('description', 'No aplica')?->id ?? 1;
        $model_no_apply = Modell::firstWhere('description', 'No aplica')?->id ?? 1;
        $box_no_apply = Box::firstWhere('description', 'No aplica')?->id ?? 1;
        $type_product_id = TypeProduct::firstWhere('description', 'Repuestos')?->id ?? 1;

        // Crear el nuevo producto con valores predeterminados y datos validados
        $product = Product::create([
            'name' => $validated['name'],
            'cylinder_capacities_id' => $cylinder_no_apply,
            'models_id' => $model_no_apply,
            'boxes_id' => $box_no_apply,
            'type_products_id' => $type_product_id,
            'sub_categories_id' => $validated['category'],
            'brands_id' => $validated['brand'],
            'description' => $validated['description'] ?? '',
            'code' => $validated['code'] ?? '',
            'reference' => $validated['detail'] ?? '',
            'detail' => $validated['detail'] ?? '',
            'count' => 0,
            'link' => str_replace(' ', '-', $validated['name']),
            'image' => '',
        ]);

        if ($request->hasFile('selectedImage')) {
            $route_image = $request->file('selectedImage')->store('public/images-prod/' . $product->id);
            $url = Storage::url($route_image);

            $product->image = $url;
            $product->save();
        }

        // Crear un registro en la tabla ProductStore
        $product_store = new ProductStore();
        $product_store->price = $validated['price'];
        $product_store->amount = $validated['amount'];
        $product_store->created_at = Carbon::now();
        $product_store->products_id = $product->id;  // Relacionar con el producto creado
        $product_store->stores_id = $validated['storeId'];  // Relacionar con la tienda indicada

        $product_store->save();

        // Responder con un mensaje de éxito
        return response()->json([
            'message' => 'Producto creado exitosamente.',
            'product' => $product
        ], 201);  // Código de estado 201 para recurso creado
    }

    public function updateWork(Request $request, $id)
    {
        try {
            // Buscar el trabajo por ID
            $work = Work::findOrFail($id);

            // Validar los datos del request
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            // Actualizar los campos
            $work->update($validatedData);

            return response()->json([
                'message' => 'Trabajo actualizado exitosamente.',
                'data' => $work,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el trabajo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un trabajo.
     */
    public function destroyWork($id)
    {
        try {
            // Buscar el trabajo por ID
            $work = Work::findOrFail($id);

            // Verificar si el trabajo tiene una imagen asociada
            if ($work->image) {
                // Extraer la ruta relativa del archivo (elimina el prefijo '/storage/')
                $relativePath = str_replace('/storage/', 'public/', $work->image);

                // Eliminar la imagen del almacenamiento
                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }

            // Eliminar el trabajo
            $work->delete();

            return response()->json([
                'message' => 'Trabajo e imagen eliminados exitosamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el trabajo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('brand')->select('products.*');

            // Filtrar por búsqueda si existe algún valor
            if (!empty($request->search['value'])) {
                $searchValue = $request->search['value'];

                // Divide la búsqueda en palabras individuales
                $searchWords = explode(' ', $searchValue);

                // Filtra buscando cada palabra en el campo 'name'
                $products = $products->where(function ($query) use ($searchWords) {
                    foreach ($searchWords as $word) {
                        $query->where('products.name', 'like', "%{$word}%");
                    }
                });
            }

            return DataTables::of($products)
                ->addColumn('checkbox', function ($product) {
                    return '<input style="margin-top: .75rem;" type="checkbox" onclick="myCheckbox(' . $product->id . ')" id="checkbox-' . $product->id . '">';
                })
                ->addColumn('name', function ($product) {
                    return '<p class="text-xs font-weight-bold mb-0">' . $product->name . '</p>';
                })
                ->addColumn('brand', function ($product) {
                    return '<p class="text-xs font-weight-bold mb-0">' . $product->brand->description . '</p>';
                })
                ->addColumn('amount', function ($product) {
                    return '<input type="number" min="1" id="amount-' . $product->id . '" class="form-control">';
                })
                ->addColumn('price', function ($product) {
                    return '<input type="number" min="1" id="price-' . $product->id . '" class="form-control">';
                })
                ->rawColumns(['checkbox', 'name', 'brand', 'amount', 'price'])
                ->make(true);
        }
    }
}
