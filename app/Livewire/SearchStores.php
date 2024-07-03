<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Municipality;
use App\Models\ProductStore;
use App\Models\SearchUser;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStores extends Component
{
    use WithPagination;

    public $paginate = 10;

    public function render()
    {
        // Extract parameters from the URL
        if (!isset(explode('?', $_SERVER['REQUEST_URI'])[1])) {
            $array_data = explode('&', redirect()->getUrlGenerator()->previous());
        } else {
            $array_data = explode('&', explode('?', $_SERVER['REQUEST_URI'])[1]);
        }

        $state_id = explode('=', $array_data[0])[1];
        $municipalities_id = explode('=', $array_data[1])[1];
        $sector_id = explode('=', $array_data[2])[1];
        $categories_id = explode('=', $array_data[3])[1];
        $product_search = explode('=', $array_data[4])[1];

        $search = str_replace('+', ' ', $product_search);
        $finalSearch = $search;
        $municipalityId = $municipalities_id;
        $locationStores = 'sector';

        // Build the base query for stores
        $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId);

        // Add sector filter if applicable
        if ($sector_id !== 'Todos') {
            $storeQuery->where('sectors_id', $sector_id);
        }

        // Add product filter including subcategory check if categories_id is specified
        $storeQuery->whereHas('products', function ($query) use ($search, $categories_id) {
            $query->where('name', 'like', '%' . $search . '%');
            if ($categories_id !== 'Categoria') {
                $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                    $subQuery->where('categories_id', $categories_id);
                });
            }
        });

        // Eager load products with subcategory filter if categories_id is specified
        $storeQuery->with(['products' => function ($query) use ($search, $categories_id) {
            $query->where('name', 'like', '%' . $search . '%');
            if ($categories_id !== 'Categoria') {
                $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                    $subQuery->where('categories_id', $categories_id);
                });
            }
        }, 'municipality']);

        $stores = $storeQuery->get();

        if ($stores->isEmpty()) {
            $locationStores = 'municipality';
            $storeQuery = Store::where('status', true)->where('municipalities_id', $municipalityId);

            // Add product filter including subcategory check if categories_id is specified
            $storeQuery->whereHas('products', function ($query) use ($search, $categories_id) {
                $query->where('name', 'like', '%' . $search . '%');
                if ($categories_id !== 'Categoria') {
                    $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                        $subQuery->where('categories_id', $categories_id);
                    });
                }
            });

            // Eager load products with subcategory filter if categories_id is specified
            $storeQuery->with(['products' => function ($query) use ($search, $categories_id) {
                $query->where('name', 'like', '%' . $search . '%');
                if ($categories_id !== 'Categoria') {
                    $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                        $subQuery->where('categories_id', $categories_id);
                    });
                }
            }, 'municipality'])->get();

            $stores = $storeQuery->get();

            if ($stores->isEmpty()) {
                $locationStores = 'state';
                $municipalities = Municipality::where('states_id', $state_id)->pluck('id');
    
                $stores = Store::where('status', true)
                    ->whereIn('municipalities_id', $municipalities)
                    ->whereHas('products', function ($query) use ($search, $categories_id) {
                        $query->where('name', 'like', '%' . $search . '%');
                        if ($categories_id !== 'Categoria') {
                            $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                                $subQuery->where('categories_id', $categories_id);
                            });
                        }
                    })
                    ->with(['products' => function ($query) use ($search, $categories_id) {
                        $query->where('name', 'like', '%' . $search . '%');
                        if ($categories_id !== 'Categoria') {
                            $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                                $subQuery->where('categories_id', $categories_id);
                            });
                        }
                    }, 'municipality'])->get();
    
                if ($stores->isEmpty()) {
                    $locationStores = 'country';
                    $municipalities = Municipality::pluck('id');
    
                    $stores = Store::where('status', true)
                        ->whereIn('municipalities_id', $municipalities)
                        ->whereHas('products', function ($query) use ($search, $categories_id) {
                            $query->where('name', 'like', '%' . $search . '%');
                            if ($categories_id !== 'Categoria') {
                                $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                                    $subQuery->where('categories_id', $categories_id);
                                });
                            }
                        })
                        ->with(['products' => function ($query) use ($search, $categories_id) {
                            $query->where('name', 'like', '%' . $search . '%');
                            if ($categories_id !== 'Categoria') {
                                $query->whereHas('subCategory', function ($subQuery) use ($categories_id) {
                                    $subQuery->where('categories_id', $categories_id);
                                });
                            }
                        }, 'municipality'])->get();
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
                        $search->users_id = Auth::user()->id;
                        $search->stores_id = $store->id;
                        $search->products_id = $product_store->products_id;
                        $search->created_at = now();
                        $search->save();
                    }
                }
            }
        }

        return view('livewire.search-stores', ['stores' => $stores, 'locationStores' => $locationStores, 'product_search' => $finalSearch]);
    }
}
