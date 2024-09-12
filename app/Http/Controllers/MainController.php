<?php

namespace App\Http\Controllers;

use App\Models\AditionalPicturesProduct;
use App\Models\Box;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Country;
use App\Models\cylinderCapacity;
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
use App\Models\Plan;
use App\Models\PlanContracting;
use App\Models\SearchUser;
use App\Models\Sector;
use App\Models\SignalAux;
use App\Models\State;
use App\Models\SubCategory;
use App\Models\TypeProduct;
use App\Models\TypePublicity;
use App\Models\User;
use App\Notifications\ResetPasswordApi;
use App\Notifications\VerifiedEmailApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class MainController extends Controller
{
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
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states
        ];
        return view('register-data-store', $array_data);
    }


    public function registerDataTaller()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states
        ];
        return view('register-data-taller', $array_data);
    }

    public function registerDataGrua()
    {
        $type_stores = TypeStore::all();
        $municipalities = Municipality::all();
        $states = State::all();
        $array_data = [
            'type_stores' => $type_stores,
            'municipalities' => $municipalities,
            'states' => $states
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'profiles_id' => 3,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->notify(new VerifiedEmailApi($user, $request->token));

        return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
    }

    public function sendVerifiedEmailApi(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at != null) {
            return response()->json(['error' => 'Correo verificado'], 422);
        }
        $user->notify(new VerifiedEmailApi($user, $request->token));
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

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            if ($user->email_verified_at == null) {
                return response()->json(['error' => 'Por favor verifica tu correo electronico'], 422);
            }

            $user->token = $request->token_fcm;
            $user->save();

            $store = Store::where('users_id', $user->id)->first();

            if ($store != null) {
                return response()->json(['user' => $user, 'store' => $store->id], 200);
            } else {
                return response()->json(['user' => $user], 200);
            }
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 422);
        }
    }

    public function verifiedApi(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user)
            $user->email_verified_at = Carbon::now();
        $user->save();
        return response()->json(['user' => $user], 200);
    }

    public function logoutApi(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function current(Request $request)
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function subscriptionsApi(Request $request)
    {
        $user = User::where('email', $request->email)->first();
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
                'image' => $key->store->image,
                'conversation' => $conversation
            ];
            $array_subscriptions[] = $store;
        }
        return response()->json($array_subscriptions, 200);
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

    public function storeDetail(Request $request)
    {
        $store = Store::find($request->store_id);
        $conversation = Conversation::where('stores_id', $store->user->id)->where('users_id', $request->user_id)->first();
        if ($conversation == null && $store->users_id != $request->user_id) {
            $conversation = new Conversation();
            $conversation->users_id = $request->user_id;
            $conversation->stores_id = $store->users_id;
            $conversation->created_at = Carbon::now();
            $conversation->save();
        }
        $subscription = count(Subscription::where('stores_id', $request->store_id)->where('users_id', $request->user_id)->get()) > 0;
        return response()->json(['store' => $store, 'subscription' => $subscription, 'conversation' => $conversation], 200);
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
        $search = str_replace('-', ' ', $request->product_search);

        // Obtén el ID de la ciudad
        $store_id = $request->store_id;

        // Construir la consulta de búsqueda booleana con comillas dobles para coincidencia exacta
        $searchQuery = '"' . $search . '"';

        // Obtener la tienda que coincide con el ID de la tienda proporcionado
        $store = Store::find($store_id);

        // Buscar productos que coincidan con la búsqueda y estén asociados a la tienda encontrada
        $products = $store->products()->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$searchQuery])->get();

        // Si no se encontraron productos, buscar con coincidencias parciales
        if ($products->isEmpty()) {
            $searchQuery = $search . '*';

            $products = $store->products()->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$searchQuery])->get();
        }

        // Retornar los productos encontrados
        return response()->json($products, 200);
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
        $publicity = Publicity::find($request->id);
        return response()->json(['publicity' => $publicity], 200);
    }

    public function pubilicitiesDetail(Request $request)
    {
        $publicities = Publicity::where('id', '!=', $request->id)->get();
        return response()->json(['publicities' => $publicities], 200);
    }

    public function updateDataApi(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        return response()->json(['user' => $user], 200);
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
            'reference' => $product->reference,
            'image' => $product->image,
            'amount' => $product_store->amount,
            'price' => $product_store->price,
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

    public function getStoreSearch(Request $request)
    {
        $search = str_replace('-', ' ', $request->query('query'));
        $municipalityId = $request->municipalityId;
        $state_id = $request->stateId;
        $sector_id = $request->sectorId;
        $locationStores = 'sector';

        // Build the base query for stores
        $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId);

        // Add sector filter if applicable
        if ($sector_id !== 'Todos') {
            $storeQuery->where('sectors_id', $sector_id);
        }

        // Add product filter including subcategory check if categories_id is specified
        $storeQuery->whereHas('products', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        // Eager load products with subcategory filter if categories_id is specified
        $storeQuery->with(['products' => function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }, 'municipality']);

        $stores = $storeQuery->paginate(10);

        if ($stores->isEmpty()) {
            $locationStores = 'municipality';
            $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId);

            // Add product filter including subcategory check if categories_id is specified
            $storeQuery->whereHas('products', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });

            // Eager load products with subcategory filter if categories_id is specified
            $storeQuery->with(['products' => function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }, 'municipality'])->paginate(10);

            $stores = $storeQuery->paginate(10);

            if ($stores->isEmpty()) {
                $locationStores = 'state';
                $municipalities = Municipality::where('states_id', $state_id)->pluck('id');

                $stores = Store::where('status', true)
                    ->whereIn('municipalities_id', $municipalities)
                    ->whereHas('products', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->with(['products' => function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    }, 'municipality'])->paginate(10);

                if ($stores->isEmpty()) {
                    $locationStores = 'country';
                    $municipalities = Municipality::pluck('id');

                    $stores = Store::where('status', true)
                        ->whereIn('municipalities_id', $municipalities)
                        ->whereHas('products', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        })
                        ->with(['products' => function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        }, 'municipality'])->paginate(10);
                }
            }
        }

        if (!$stores->isEmpty()) {
            foreach ($stores as $store) {
                $product_store = ProductStore::where('products_id', $store->products->first()->id)
                    ->where('stores_id', $store->id)
                    ->first();
                if ($product_store != null) {
                    $searches = SearchUser::where('products_id', $product_store->products_id)->where('stores_id', $store->id)->get();
                    if ($searches->isEmpty()) {
                        $search = new SearchUser();
                        $search->users_id = $request->userId;
                        $search->stores_id = $store->id;
                        $search->products_id = $product_store->products_id;
                        $search->created_at = now();
                        $search->save();
                    }
                }
            }
        }

        return response()->json(['stores' => $stores, 'locationStores' => $locationStores], 200);
    }

    public function getStoreSearch2(Request $request)
    {
        // Obtén el valor del parámetro 'query' de la consulta
        $query = $request->query('query');

        $municipalityId = $request->municipalityId;
        $state_id = $request->stateId;
        $sector_id = $request->sectorId;
        $locationStores = 'sector';

        // Build the base query for stores
        $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId)->where('type_stores_id', $request->type)->with('municipality');

        // Add sector filter if applicable
        if ($sector_id !== 'Todos') {
            $storeQuery->where('sectors_id', $sector_id);
        }

        if ($query !== '') {
            $storeQuery->where('name', 'like', '%' . $query . '%');
        }

        $stores = $storeQuery->paginate(10);

        if ($stores->isEmpty()) {
            $locationStores = 'municipality';
            $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId)->where('type_stores_id', $request->type)->with('municipality');
            if ($query !== '') {
                $storeQuery->where('name', 'like', '%' . $query . '%');
            }
            $stores = $storeQuery->paginate(10);

            if ($stores->isEmpty()) {
                $locationStores = 'state';
                $municipalities = Municipality::where('states_id', $state_id)->pluck('id');

                $stores = Store::where('status', true)->whereIn('municipalities_id', $municipalities)->where('type_stores_id', $request->type)->with('municipality');
                if ($query !== '') {
                    $stores->where('name', 'like', '%' . $query . '%');
                }

                $stores = $stores->paginate(10);

                if ($stores->isEmpty()) {
                    $locationStores = 'country';
                    $municipalities = Municipality::pluck('id');
                    $stores = Store::where('status', true)->whereIn('municipalities_id', $municipalities)->with('municipality');
                    if ($query !== '') {
                        $stores->where('name', 'like', '%' . $query . '%');
                    }

                    $stores = $stores->paginate(10);
                }
            }
        }

        // Retorna la respuesta JSON
        return response()->json(['stores' => $stores, 'locationStores' => $locationStores], 200);
    }

    public function getProductsSearch($query)
    {
        $string = $query;
        $products = Product::whereHas('stores', function ($query) {
            $query->where('status', 1); // Filtra tiendas activas
        })->where('name', 'like',  '%' . $string . '%')->get();

        return response()->json($products);
    }

    public function getProductsSearch2($query, $id)
    {
        $string = $query;
        $products = Product::whereHas('stores', function ($query) use ($id) {
            $query->where('stores.id', $id);   // Filtra tiendas activas
        })->where('name', 'like', '%' . $string . '%')->get();
        return response()->json($products);
    }

    public function getChats($userId)
    {
        $final_array = [];

        // Obtener conversaciones del usuario y las asociadas a su tienda (si tiene)
        $conversations = Conversation::where('users_id', $userId)->orWhere('stores_id', $userId)->with(['user', 'store', 'messages'])->get();

        // Ordenar las conversaciones por la fecha del mensaje más reciente
        $conversations = $conversations->sortByDesc(function ($conversation) {
            // Obtener la fecha del último mensaje
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
                if ($my_user->id != $user->id) {
                    $user = $user;
                } else {
                    $user = $user2;
                }

                if ($user->store) {
                    $final_array[$key]['user_name'] = $user->store->name;
                    $final_array[$key]['user_img'] = $user->store->image;
                } else {
                    $final_array[$key]['user_name'] = $user->name;
                    $final_array[$key]['user_img'] = $user->image;
                }

                if ($final_array[$key]['user_img'] == null || $final_array[$key]['user_img'] == '') {
                    $letter = strtoupper($final_array[$key]['user_name'][0]);
                    $final_array[$key]['user_img'] = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
                } else {
                    $final_array[$key]['user_img'] = $final_array[$key]['user_img'];
                }

                $final_array[$key]['last_message'] = Crypt::decryptString($lastMessage->content);
                $final_array[$key]['last_message_time'] = $lastMessage->created_at;
                $final_array[$key]['last_message_status'] = $lastMessage->status;
                $final_array[$key]['last_message_from'] = $lastMessage->from;
                $final_array[$key]['id'] = $conversation->id;
            }
        }

        return response()->json(array_values($final_array));
    }

    public function getInfoHome($userId)
    {
        $date = Carbon::now();
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) use ($date) {
            $query->where('status', true)->where('date_init', '<=', $date)->where('date_end', '>=', $date);
        })->take(6)->get();

        $stores2 = collect();
        $stores3 = collect();

        if (!Auth::check()) {
            $stores2 = SearchUser::with(['store', 'store.municipality'])
                ->limit(9)
                ->get();

            $stores3 = SearchUser::with(['product', 'store'])
                ->limit(9)
                ->get();
        } else {
            $userId = Auth::id();

            $stores2 = SearchUser::where('users_id', $userId)
                ->with(['store', 'store.municipality'])
                ->limit(9)
                ->get();

            $stores3 = SearchUser::where('users_id', $userId)
                ->with(['product', 'store'])
                ->limit(9)
                ->get();
        }

        $array_stores = [];
        $array_stores_final = [];
        $array_products = [];
        $array_products_final = [];

        foreach ($stores2 as $store) {
            $store_id = $store->store->id; // Asegúrate de que 'store' y 'id' son correctos
            if (!in_array($store_id, $array_stores)) {
                $array_stores[] = $store_id;
                $array_stores_final[] = $store->store;
            }
        }

        foreach ($stores3 as $product) {
            $product_id = $product->product->id; // Asegúrate de que 'product' y 'id' son correctos
            if (!in_array($product_id, $array_products)) {
                $array_products[] = $product_id;
                $array_products_final[] = $product->product;
            }
        }


        $publicities = Publicity::where('date_end', '>', $date)
            ->where('status', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return response()->json([
            'publicities' => $publicities,
            'stores' => $stores,
            'lastStores' => $array_stores_final,
            'lastSearch' => $array_products_final
        ]);
    }

    public function getAllStoresPromotion(Request $request)
    {
        $stores = Store::where('status', true)->whereHas('promotions', function ($query) {
            $query->where('status', true)->where('date_init', '<=', Carbon::now())->where('date_end', '>=', Carbon::now());
        })->with('municipality')->paginate(10);
        return response()->json($stores);
    }

    public function registerStorePost(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'municipalities_id' => 'required',
            'sectors_id' => 'required',
            'name' => 'required|string|max:100|unique:stores',
            'description' => 'required|string|max:255',
            'email' => 'required|email|unique:stores',
            'address' => 'required|max:255',
            'rif' => 'required|max:255',
            'phone' => ['required', 'regex:/^(0412|0414|0416|0424|0426)\d{7}$/']
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
        $store->email = $request->email;
        $store->address = $request->address;
        $store->link = str_replace(' ', '-', $request->name);
        $store->status = false;
        $store->RIF = $request->rif;
        $store->phone = $request->phone;
        $store->score_store = 0;
        $store->created_at = Carbon::now();

        if ($request->typeStore == 'Grua') {
            $store->tipo = $request->tipo;
            $store->dimensiones = $request->dimensiones;
            $store->capacidad = $request->capacidad;
        }

        $store->save();

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
        Conversation::where('users_id', $request->users_id)->delete();

        //Cambiar perfil de usuario
        $user = User::find($request->users_id);
        if ($request->typeStore == 'Tienda') {
            $user->profiles_id = 2;
        } else if ($request->typeStore == 'Taller') {
            $user->profiles_id = 4;
        } else {
            $user->profiles_id = 5;
        }
        $user->save();

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

        return response()->json(['success' => 'Promotion created successfully'], 200);
    }

    public function getMunicipalities()
    {
        $municipalities = Municipality::all();
        $type_stores = TypeStore::all();
        return response()->json(['municipalities' => $municipalities, 'type_stores' => $type_stores], 200);
    }

    public function sendSignalAux(Request $request)
    {
        //Encontrar usuario que envia el auxilio vial
        $user = User::find($request->userId);
        $name = $user->name;

        //Encontrando tiendas que se encuentran en esa ciudad, que son ese tipo de tienda y que estan activas
        $stores = Store::whereHas('typeStore', function ($query) use ($request) {
            $query->where('description', $request->type);
        })->where('status', true)->where('municipalities_id', $request->city);

        if ($request->sector) {
            $stores->where('sectors_id', $request->sector)->get();
        }

        $stores = $stores->get();

        //Recorriendo las tiendas para enviar la notificacion a cada una de ellas
        foreach ($stores as $store) {
            $signal = new SignalAux();
            $signal->users_id = $user->id;
            $signal->stores_id = $store->user->id;
            $signal->detail = $request->description;
            $signal->status = false;
            $signal->status2 = false;
            $signal->read = false;
            $signal->created_at = Carbon::now();
            $signal->save();

            $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            // Obtener el servicio de mensajería
            $messaging = $firebase->createMessaging();

            // Crear el mensaje
            $message = CloudMessage::fromArray([
                'token' => $store->token,  // El token del dispositivo que recibirá la notificación
                'notification' => [
                    'title' => $name,
                    'body' => 'Requiero auxilio vial',
                ],
                'data' => [ // Datos adicionales para manejar la redirección
                    'click_action' => 'OPEN_URL',
                    'url' => '/signals-aux',  // Ruta donde quieres redirigir al usuario
                    'android' => [
                        'priority' => 'high',
                    ],
                ],
            ]);

            // Enviar el mensaje
            $messaging->send($message);
        }

        return response()->json($stores, 200);
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

    public function getSignalsAux(Request $request)
    {
        $user_id = $request->userId;
        $signals_aux = SignalAux::where('users_id', $user_id)->get();
        $array_data = [];
        foreach ($signals_aux as $key => $signal) {
            $user = User::find($signal->stores_id);
            $array_data[$key]['id'] = $signal->id;
            $array_data[$key]['name'] = $user->store->name;
            $array_data[$key]['image'] = $user->store->image;
            $array_data[$key]['created_at'] = $signal->created_at;
            $array_data[$key]['detail'] = $signal->detail;
            $array_data[$key]['status'] = $signal->status;
            $array_data[$key]['status2'] = $signal->status2;
            $array_data[$key]['read'] = $signal->read;
        }
        $signals_aux2 = SignalAux::where('stores_id', $user_id)->get();
        $array_data2 = [];
        foreach ($signals_aux2 as $key => $signal) {
            $user = User::find($signal->users_id);
            if ($user->store) {
                $array_data2[$key]['id'] = $signal->id;
                $array_data2[$key]['name'] = $user->store->name;
                $array_data2[$key]['image'] = $user->store->image;
                $array_data2[$key]['created_at'] = $signal->created_at;
                $array_data2[$key]['detail'] = $signal->detail;
                $array_data2[$key]['status'] = $signal->status;
                $array_data2[$key]['status2'] = $signal->status2;
                $array_data2[$key]['read'] = $signal->read;
            } else {
                $array_data2[$key]['id'] = $signal->id;
                $array_data2[$key]['name'] = $user->name;
                $array_data2[$key]['image'] = $user->image;
                $array_data2[$key]['created_at'] = $signal->created_at;
                $array_data2[$key]['detail'] = $signal->detail;
                $array_data2[$key]['status'] = $signal->status;
                $array_data2[$key]['status2'] = $signal->status2;
                $array_data2[$key]['read'] = $signal->read;
            }
        }

        return response()->json(['array_send' => $array_data, 'array_recive' => $array_data2], 200);
    }

    public function changeStatusSignalsAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        if ($signal == null) {
            return response()->json(['id' => 0], 200);
        }
        $signal->status = true;
        $signal->save();

        // Eliminar todas las filas relacionadas con el usuario excepto la actual
        SignalAux::where('users_id', $signal->users_id)
            ->where('id', '!=', $signal->id)
            ->delete();

        $conversation = Conversation::where('users_id', $signal->users_id)->first();
        if ($conversation == null) {
            $conversation = new Conversation();
            $conversation->users_id = $signal->users_id;
            $conversation->stores_id = $signal->stores_id;
            $conversation->created_at = Carbon::now();
            $conversation->save();
        }

        return response()->json(['id' => $conversation->id], 200);
    }

    public function removeSignalsAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        if ($signal != null) {
            $signal->delete();
        }
        return response()->json('ok', 200);
    }

    public function closeSignalsAux(Request $request)
    {
        $signal = SignalAux::find($request->id);
        $signal->read = true;
        $signal->save();
        return response()->json('ok', 200);
    }

    public function qualitySignal(Request $request)
    {
        $signal = SignalAux::find($request->id);
        $store = Store::where('users_id', $signal->stores_id)->first();
        if ($store != null) {
            $store->score_store = ($request->rate + $store->score_store) / 2;
            $store->save();
            $signal->status2 = true;
            $signal->save();
            return response()->json('ok', 200);
        }
        return response()->json('La tienda no fue encontrada', 401);
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
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->notify(new ResetPasswordApi($request->token));

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
                    'sub_categories_id',
                    'brands_id',
                    'models_id',
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
}
