<style>
    .contenedor-imagen {
        position: relative;
        display: inline-block;
        height: 25rem;
    }

    .imagen {
        max-width: 100%;
        height: 25rem;
        width: 100rem !important;
        object-fit: cover;
    }

    .boton-flotante {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .boton-flotante:hover {
        background-color: #0056b3;
        transform: scale(1.1);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .input-imagen {
        display: none;
    }

    .contenedor-imagen-2 {
        position: relative;
        display: inline-block;
        height: 25rem;
    }

    .imagen-2 {
        max-width: 100%;
        height: 25rem;
        width: 100rem !important;
    }

    .boton-flotante-2 {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .boton-flotante-2:hover {
        background-color: #0056b3;
        transform: scale(1.1);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .input-imagen-2 {
        display: none;
    }
</style>


<div>
    <?php
    $condition = Auth::user()->profiles_id == 1 || Auth::user()->profiles_id == 2 || Auth::user()->profiles_id == 4 || Auth::user()->profiles_id == 5;
    if ($condition) {
        if (Auth::user()->store != null) {
            if (Auth::user()->store->id != $store->id) {
                $condition = false;
            }
        } else {
            $condition = false;
        }
    }
    $condition2 = $store->typeStore->description == env('TIPO_TALLER') || $store->typeStore->description == env('TIPO_GRUA');
    ?>
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div>
        @if (session()->has('associateProduct'))
            <div class="alert alert-success">
                {{ session('associateProduct') }}
            </div>
        @endif
    </div>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="contenedor-imagen">
                    <img src="{{ asset($store->image2) }}" class="imagen">
                    @if($condition)
                    <button id="boton-flotante" class="boton-flotante" onclick="mostrarInput()">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                    <input type="file" id="input-imagen" class="input-imagen" accept="image/*"
                        onchange="mostrarImagen(event)">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="scan" id="qrcode" wire:ignore></div>
    <div class="row">
        <div class="col-md-1 offset-md-6 d-flex align-items-center mt-3">
            @if ($subscribed)
                <button type="button" class="btn btn-subs" style="display: flex;align-items: center;"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Suscrito
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="cursor: pointer">
                    <li><a class="dropdown-item" wire:click="nullSubscribe">Anular</a></li>
                </ul>
            @else
                @if (!$condition)
                    <button class="btn btn-subs" wire:click="subscribe">Suscribete</button>
                @endif
            @endif
        </div>
        @if (!$condition2)
            <div class="col-12 col-md-5">
                <?php
                $link_store = str_replace(' ', '-', $store->name);
                ?>
                <form action="/tienda/{{ $link_store }}" class="row mt-3" id="form-search" autocomplete="off">
                    <div class="col-4 d-none d-md-flex align-items-center justify-content-center">
                        <select id="select-search-categories" name="tBGZall1t5CCeUqrQOkM" class="select-search">
                            <option selected>Categoria</option>
                            @foreach ($categories as $key)
                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 col-md-6 d-flex align-items-center justify-content-center">
                        <input class="input-search" name="product" placeholder="Busca el repuesto o accesorio"
                            type="text">
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-magnifying-glass icons-search pointer" onclick="searchData()"></i>
                        <i class="fa-solid fa-microphone icons-search pointer"></i>
                    </div>
                </form>
            </div>
        @endif
    </div>
    <?php
    $link_whatssap = str_replace('04', '4', $store->phone);
    ?>
    <a style="position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background-color: #28e26d;
    color: white;
    border: none;
    padding: 5px 20px;
    cursor: pointer;
    font-size: 3rem;
    border-radius: 100%;"
        href="https://wa.me/58{{ $link_whatssap }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
    <div class="row mt-3">
        <h2>{{ $store->name }}</h2>
        <div class="col-12 col-lg-10">
            <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2 @if ($product_detail == null && $search_products == false) active @endif"
                        id="productEspecification" data-bs-toggle="tab" data-bs-target="#especificationProduct"
                        type="button" role="tab" aria-controls="especificationProduct"
                        aria-selected="true">Información de la tienda</button>
                </li>

                @if (!$condition2)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($search_products == true) active @endif" id="productDescription"
                            data-bs-toggle="tab" data-bs-target="#descriptionProduct" type="button" role="tab"
                            aria-controls="descriptionProduct" aria-selected="false">Productos</button>
                    </li>
                @endif

                @if ($product_detail != null)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="productDescription2" data-bs-toggle="tab"
                            data-bs-target="#descriptionProduct2" type="button" role="tab"
                            aria-controls="descriptionProduct2" aria-selected="false">Detalle del producto</button>
                    </li>
                @endif

                @if ($condition)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link me-2" id="subs1" data-bs-toggle="tab" data-bs-target="#subs"
                            type="button" role="tab" aria-controls="subs"
                            aria-selected="true">Suscriptores</button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link me-2" id="promos" data-bs-toggle="tab" data-bs-target="#promo"
                            type="button" role="tab" aria-controls="promo" aria-selected="true">
                            @if ($condition2)
                                Publicidad
                            @else
                                Promociones
                            @endif
                        </button>
                    </li>
                @endif
            </ul>
            <div class="tab-content pt-3 product-details-tab-content" id="myTabContent">
                <div class="tab-pane fade @if ($product_detail == null && $search_products == false) active show @endif"
                    id="especificationProduct" role="tabpanel" aria-labelledby="productEspecification">
                    <div class="container">
                        <div>
                            @if (session()->has('messageUpdateStore'))
                                <div class="alert alert-success">
                                    {{ session('messageUpdateStore') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-8 d-flex justify-content-center" style="position: relative;">
                                <div class="contenedor-imagen-2">
                                    <img src="{{ asset($store->image) }}" alt="Imagen" class="imagen">
                                    @if($condition)
                                    <button id="boton-flotante-2" class="boton-flotante-2" onclick="mostrarInput2()">
                                        <i class="fa-solid fa-camera"></i>
                                    </button>
                                    <input type="file" id="input-imagen-2" class="input-imagen-2"
                                        accept="image/*" onchange="mostrarImagen(event)">
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="container" style="margin-top: 5rem;">
                                    <div class="row">
                                        <div class="col-12">
                                            @if ($condition)
                                                <button class="btn btn-outline-primary w-100 mb-3"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal2">Editar
                                                    datos de la tienda</button>
                                            @endif
                                            <h3>{{ $store->name }}</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><b>Descripción:</b></p>
                                            <p>{{ $store->description }}</p>
                                            <p><b>Dirección:</b></p>
                                            <p><i
                                                    class="fa-solid fa-location-dot me-2"></i>{{ $store->city->municipality->state->name }}
                                                - {{ $store->city->name }} - {{ $store->address }}</p>
                                            <p><b>Correo electrónico:</b></p>
                                            <p>{{ $store->email }}</p>
                                            <p><b>Número de contacto:</b></p>
                                            <p>{{ $store->phone }}</p>
                                            @if ($store->typeStore->description == env('TIPO_GRUA'))
                                                <p><b>Capacidad de la grúa:</b></p>
                                                <p>{{ $store->capacidad }}</p>
                                                <p><b>Tipo de grúa:</b></p>
                                                <p>{{ $store->tipo }}</p>
                                                <p><b>Dimensiones de la grúa:</b></p>
                                                <p>{{ $store->dimensiones }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($search_products == true) active show @endif"
                    id="descriptionProduct" role="tabpanel" aria-labelledby="productDescription">
                    <div class="container">
                        <div class="row">
                            @if ($condition)
                                <div class="col-md-4 offset-md-4">
                                    <button class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal4"><i class="fa-solid fa-plus me-3"></i>Asociar
                                        producto</button>
                                </div>
                            @endif
                        </div>
                        <div class="row" id="productos-container">
                            @if ($showMessageNotFoundProducts)
                                <div class="alert alert-info">
                                    No hemos encontrado productos que coincidieran con tu busqueda, aqui puedes ver otras opciones.
                                </div>
                            @endif
                            @if (count($products) == 0)
                                <div class="alert alert-info">
                                    Esta tienda aun no tiene productos asociados.
                                </div>
                            @endif
                            @foreach ($products as $product)
                                <div class="col-12 col-md-4 mt-3">
                                    <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/{{ $product->link }}">
                                        <div class="card card-store" style="height: 100%;">
                                            <div class="zoom-container">
                                                <img class="zoomed-image" src="{{ asset($product->image) }}">
                                            </div>
                                            <div class="card-body" style="padding-bottom: 4rem;">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                <p class="card-text">{{ $product->description }}</p>
                                                <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/{{ $product->link }}"
                                                    class="btn btn-warning position-absolute bottom-0 end-0"
                                                    style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        @if ($products_total > 6)
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button class="btn btn btn-warning" id="load-products">Cargas más..</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($product_detail != null)
                    <div class="tab-pane fade active show" id="descriptionProduct2" role="tabpanel"
                        aria-labelledby="productDescription2">
                        <div class="container">
                            <div>
                                @if (session()->has('messageUpdateProduct'))
                                    <div class="alert alert-success">
                                        {{ session('messageUpdateProduct') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <div class="container mt-5">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card" style="width: 7rem;height: 5rem;">
                                                            <div class="card-body" style="padding: .5rem;">
                                                                <img class="thumbnail img-fluid"
                                                                    style="height: 4rem;
                                                        width: 100%;
                                                      "
                                                                    src="{{ asset($product_detail->image) }}"
                                                                    style="height: 100%;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (isset($product_detail->aditionalPictures))
                                                        @foreach ($product_detail->aditionalPictures as $key)
                                                            <div class="col-12 mt-3">
                                                                <div class="card" style="width: 7rem;height: 5rem;">
                                                                    <div class="card-body" style="padding: .5rem;">
                                                                        <img class="thumbnail img-fluid"
                                                                            style="height: 4rem;
                                                                width: 100%;
                                                              "
                                                                            src="{{ asset($key->image) }}"
                                                                            style="object-fit: cover;width: 7rem;height: 4rem;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <!-- Agrega más imágenes secundarias según sea necesario -->
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="card ms-5">
                                                    <div class="card-body" style="padding: 0rem;">
                                                        <div id="imagenPrincipalContainer">
                                                            <img id="imagenPrincipal" class="img-fluid"
                                                                src="{{ asset($product_detail->image) }}"
                                                                style="height: 24rem;cursor:zoom-in;width: 100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="container" style="margin-top: 5rem;position: relative">
                                        <div id="lupa" class="d-none"
                                            style="position:absolute;top: 0rem;background: rgb(255, 255, 255);width: 20rem;height: 15rem;background-image: url('{{ asset($product_detail->image) }}');background-repeat: no-repeat;">
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                @if ($condition)
                                                    <button class="btn btn-outline-primary w-100 mb-3"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal3">Editar
                                                        datos del producto</button>
                                                @endif
                                                <h4 style="color: gray;">
                                                    {{ $this->product_detail->brand->description }}</h4>
                                                <h3>{{ $this->product_detail->name }}</h3>
                                                <p>Ref. {{ $this->product_detail->reference }}</p>
                                                <p>$<b>{{ $this->product_store->price }}</b></p>
                                                <p>Iva incluido</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Código:</b></p>
                                                <p><b>Referencia:</b></p>
                                                <p><b>Detalle:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p>{{ $this->product_detail->code }}</p>
                                                <p>{{ $this->product_detail->reference }}</p>
                                                <p>{{ $this->product_detail->detail }}</p>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <h4 style="color: gray;">Cantidad disponible</h4>
                                                <input type="number" class="form-control w-25 mt-3"
                                                    value="{{ $this->product_store->amount }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-8">
                                    <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link me-2 active" id="productEspecification2"
                                                data-bs-toggle="tab" data-bs-target="#especificationProduct2"
                                                type="button" role="tab" aria-controls="especificationProduct2"
                                                aria-selected="true">Descripción del producto</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-3 product-details-tab-content" id="myTabContent">
                                        <div class="tab-pane fade active show" id="especificationProduct2"
                                            role="tabpanel" aria-labelledby="productEspecification2">
                                            <p>{{ $this->product_detail->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="tab-pane fade" id="subs" role="tabpanel" aria-labelledby="subs">
                    <div class="container">
                        <h4>Suscriptores</h4>
                        <div class="row">
                            <div class="col-md-6 offset-md-3" style="border-top: solid 2px #606060;">
                                <ul class="list-group">
                                    @foreach ($subscriptions as $subscription)
                                        <?php
                                        $url_profile = '';
                                        if ($subscription->user->image == null) {
                                            $letter = strtoupper($subscription->user->name[0]);
                                            $ruta_imagen = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
                                        } else {
                                            $assets = asset('');
                                            $ruta_imagen = $subscription->user->image;
                                            if (!str_contains($ruta_imagen, 'storage')) {
                                                $ruta_imagen = '/storage/' . $ruta_imagen;
                                            }
                                            if (str_contains($ruta_imagen, 'http://localhost/')) {
                                                str_replace('http://localhost/', $assets, $ruta_imagen);
                                            }
                                        }
                                        
                                        $link = '';
                                        
                                        if ($subscription->user->store !== null) {
                                            $link = '/tienda/' . str_replace(' ', '-', $subscription->user->store->name);
                                        }
                                        ?>
                                        <a href="{{ $link }}">
                                            <li class="list-group-item d-flex"
                                                style="justify-content: start;align-items: center;border: none;">
                                                <img src="{{ $ruta_imagen }}"
                                                    style="width: 3rem;
                                                    height: 3rem;
                                                    border-radius: 100%;
                                                    margin-right: 1rem;
                                                    border: solid 1px #dee2e6;
                                                    object-fit: cover;">{{ $subscription->user->name }}
                                            </li>
                                        </a>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="promo" role="tabpanel" aria-labelledby="promo">
                    @livewire('promotions', ['global_store' => $global_store, 'condition2' => $condition2])
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-2">
            <div class="row" id="publicities">
                @foreach ($publicities as $key)
                    <li class="slide"
                        style="margin-top: .5rem;border-radius: 15px;background: transparent;border: transparent;">
                        <div class="card">
                            <div class="card-body" style="padding: 0rem;">
                                <div class="contenedor-imagen" onclick="goPagePublicity({{ $key->id }})">
                                    <img src="{{ asset($key->image) }}" class="img-fluid imagen-zoom">
                                    <div class="texto-encima">{{ $key->title }}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar datos de la tienda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @livewire('update-store', ['store' => $global_store]);
            </div>
        </div>
    </div>
    <!-- Modal -->
    @if ($product_store !== null)
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar datos del producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    @livewire('update-product', ['product_store' => $product_store, 'store' => $store, 'product_detail' => $product_detail]);
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Asociar producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @livewire('associate-product', ['store' => $store])
            </div>
        </div>
    </div>
</div>
</div>



@livewireScripts

<script>
    $(document).ready(function() {
        var page = 2;
        var loading = false;

        function updateAds() {
            $.ajax({
                url: '/get-random-ads',
                type: 'GET',
                success: function(response) {
                    //Ocultar las publicidades actuales
                    $('#publicities').fadeOut('slow', function() {
                        //Limpiar las publicidades actuales
                        $('#publicities').empty();

                        //Mostrar las nuevas publicidades
                        $.each(response, function(index, ad) {
                            $('#publicities').append(`<li class="slide" style="margin-top: .5rem;border-radius: 15px;background: transparent;border: transparent;">
                            <div class="card">
                                <div class="card-body" style="padding: 0rem;">
                                    <div class="contenedor-imagen" onclick="goPagePublicity(${ad.id})">
                                        <img src="{{ asset('${ad.image}') }}" class="img-fluid imagen-zoom"
                                            alt="Imagen 1">
                                        <div class="texto-encima">${ad.title}</div>
                                    </div>
                                </div>
                            </div>
                        </li>`);
                        });

                        //Mostrar las nuevas publicidades
                        $('#publicities').fadeIn('slow');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        $("#load-products").click((e) => {
            loadProducts();
        });

        function showAlertTime() {
            Swal.fire({
                toast: true,
                position: 'center',
                title: "Cargando por favor espere...",
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }

        function queryExistMoreProdsInBd() {
            $.ajax({
                url: '/products',
                data: {
                    'page': (page + 1),
                    'store_id': '{{ $store->id }}'
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                method: 'POST',
                success: function(response) {
                    Swal
                .close(); // O Swal.closeModal(); si estás utilizando una versión anterior a SweetAlert 2.1.0
                    var productos = response.data;
                    if (productos.length == 0) {
                        $('#load-products').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }


        function loadProducts() {
            showAlertTime();
            if (!loading) {
                loading = true;
                $.ajax({
                    url: '/products',
                    data: {
                        'page': page,
                        'store_id': '{{ $store->id }}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    method: 'POST',
                    success: function(response) {
                        queryExistMoreProdsInBd();
                        var productos = response.data;
                        if (productos.length > 0) {
                            productos.forEach(function(producto) {
                                $('#productos-container').append(
                                    `<div class="col-12 col-md-4 mt-3"><a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/${producto.link}"><div class="card card-store" style="height: 100%;"><div class="zoom-container"><img class="zoomed-image" src="{{ asset('${producto.image}') }}"></div><div class="card-body" style="padding-bottom: 4rem;"><h5 class="card-title">${producto.name}</h5><p class="card-text">${producto.description}</p><a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/${producto.link}" class="btn btn-warning position-absolute bottom-0 end-0" style="margin: .5rem;cursor: pointer;">Ver</a></div></div></a></div>`
                                );
                            });
                            page++;
                        }
                        loading = false;
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // Manejar errores si es necesario
                        Swal.close(); // Asegúrate de cerrar el spinner incluso si hay un error
                    }
                });
            }
        }

        // Llamar a la función updateAds cada 5 segundos
        setInterval(updateAds, 10000);
    });

    $('#boton-flotante').click(function() {
        $('#input-imagen').click();
    });

    $('#input-imagen').change(function(event) {
        showAlertTime();
        subirImagen(event, 'banner');
    });

    $('#boton-flotante-2').click(function() {
        $('#input-imagen-2').click();
    });

    $('#input-imagen-2').change(function(event) {
        showAlertTime();
        subirImagen(event, 'main');
    });

    function subirImagen(event, type) {
        const image = event.target.files[0];
        if (image) {
            const formData = new FormData();
            formData.append('file', image);
            formData.append('type', type);
            formData.append('stores_id', '{{$store->id}}');
            $.ajax({
                url: "{{route('upload-image-store')}}", // Reemplaza 'tu/controlador/ruta' con la ruta adecuada a tu controlador Laravel
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'center',
                        icon: 'success',
                        showConfirmButton: false,
                        title: "Imagen agregada exitosamente",
                        timer: 2000
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            Swal.fire({
                                title: value[0],
                                icon: "error",
                                timer: 2000,
                                timerProgressBar: true,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false
                            });
                        });
                    } else {
                        Swal.fire({
                            title: "Hubo un problema al procesar la solicitud",
                            icon: "error",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    }
</script>
