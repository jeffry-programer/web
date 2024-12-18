<?php
    function quitar_tildes($string) {
        $originales = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú');
        $reemplazos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
        $string = str_replace($originales, $reemplazos, $string);
        return str_replace(' ', '-', $string);
    }
?>

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

    /* Personalizar los radio buttons */
    .form-check-input {
        border-radius: 50%;
        width: 18px;  /* Ajusta el tamaño del radio button */
        height: 18px;
        cursor: pointer;
        border: solid;
    }

    /* Separar cada fila de los planes */
    .mb-3 {
        margin-bottom: 15px; /* Controla el espacio entre los planes */
    }

    .cursor-pointer{
        cursor: pointer;
    }

    .btn-confirm {
        background-color: cornflowerblue !important; /* Verde vivo */
        margin-right: 1rem;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .btn-confirm:hover {
        background-color: rgb(79, 134, 236) !important;
    }
</style>


<div>
    <?php
    $condition = Auth::user()->id == $store->users_id;
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
                    <?php
                    if ($store->image2 != null) {
                        $imagen_banner = $store->image2;
                    } else {
                        $imagen_banner = 'images/1.jpg';
                    }
                    ?>
                    <img src="{{ asset($imagen_banner) }}" class="imagen">
                    @if ($condition)
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
    @livewire('subscribe', ['subscribed' => $subscribed, 'store' => $store, 'condition' => $condition, 'condition2' => $condition2, 'categories' => $categories, 'search' => $search, 'category' => $category])
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

                @if (!$condition2 && count($products_promotion) > 0)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#promotions" type="button"
                            role="tab" aria-controls="promotions" aria-selected="false">Promociones</button>
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
                                Promocionar
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
                                    <img src="{{ $store->image ? asset($store->image) : asset('images/1.jpg') }}" alt="Imagen" class="imagen">
                                    @if ($condition)
                                        <button id="boton-flotante-2" class="boton-flotante-2"
                                            onclick="mostrarInput2()">
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
                                                    datos</button>
                                            @endif
                                            <h3>{{ $store->name }}</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><b>Descripción:</b></p>
                                            <p>{{ $store->description }}</p>
                                            @if(Auth::user()->store)
                                                @if(Auth::user()->store->id == $store->id)
                                                    <p><b>Plan Contratado:</b></p>
                                                    <p>
                                                        <b>{{ $store->planContrating->plan->description }}:</b>
                                                        Desde <b>{{ \Carbon\Carbon::parse($store->planContrating->date_init)->translatedFormat('d M Y') }}</b>
                                                        hasta <b>{{ \Carbon\Carbon::parse($store->planContrating->date_end)->translatedFormat('d M Y') }}</b>
                                                    </p>                
                                                    @if(!$renovation)  
                                                        <p style="color: #74abff; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#renewPlanModal"><b>Renovar Plan</b></p>
                                                    @else
                                                        <p style="color: #565f6d;"><b>Pendiente aprobación</b></p>
                                                    @endif
                                                @endif  
                                            @endif                 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal de Renovación de Plan -->
                            <div class="modal fade" id="renewPlanModal" tabindex="-1" aria-labelledby="renewPlanModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="renewPlanModalLabel">Renovar Plan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Seleccione uno de nuestros planes</p>
                                            @foreach ($plans as $plan)
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <h5><b>Plan {{ $plan->description }}</b></h5>
                                                        <p>{{ $plan->days }} días - REF {{ $plan->price }} - {{ number_format(($plan->price * $rate), 2, ',', '.') }} Bs</p>
                                                    </div>
                                                    <div>
                                                        <input class="form-check-input" type="radio" name="plan" id="plan{{ $plan->id }}" value="{{ $plan->id }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                            <p><b>Datos Pago Movil:</b></p>
                                            <p>V-17108359</p>
                                            <p>Banco de Venezuela</p>
                                            <label for=""><b>Concepto de pago</b></label>
                                            <input type="text" class="form-control my-3" placeholder="Por favor ingrese un concepto">
                                            <label for=""><b>Comprobante</b></label>
                                            <div class="dropzone mt-3" id="myDropzone53">
                                            </div>
                                            <button class="btn btn-primary mt-3 w-100" id="saveButton">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-8" style="margin-top: 3rem;">
                                <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link me-2 active" id="productEspecification3"
                                            data-bs-toggle="tab" data-bs-target="#especificationProduct3"
                                            type="button" role="tab" aria-controls="especificationProduct3"
                                            aria-selected="true">Dirección</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3 product-details-tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="especificationProduct3"
                                        role="tabpanel" aria-labelledby="productEspecification3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><i class="fa-solid fa-location-dot me-2"></i>{{ $store->municipality->state->name }} - {{ $store->municipality->name }} - {{ $store->sector->description }} - {{ $store->address }}</p>
                                            </div>
                                            <div class="col-md-5">
                                                <p><b>Correo electrónico:</b></p>
                                                <p>{{ $store->email }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p><b>Número de contacto:</b></p>
                                                <p>{{ $store->phone }}</p>
                                            </div>
                                            @if ($store->typeStore->description == env('TIPO_GRUA'))
                                                <div class="col-md-4">
                                                    <p><b>Capacidad de la grúa:</b></p>
                                                    <p>{{ $store->capacidad }}</p>
                                                    <p><b>Tipo de grúa:</b></p>
                                                    <p>{{ $store->tipo }}</p>
                                                    <p><b>Dimensiones de la grúa:</b></p>
                                                    <p>{{ $store->dimensiones }}</p>
                                                </div>
                                            @endif
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>Categoria:</b></p>
                                                <p>{{ $store->category->description }}</p>
                                            </div>
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
                                    No hemos encontrado productos que coincidieran con tu busqueda, aqui puedes ver
                                    otras opciones.
                                </div>
                            @endif
                            @if (count($products) == 0)
                                <div class="alert alert-info">
                                    Esta tienda aun no tiene productos asociados.
                                </div>
                            @endif
                            @foreach ($products as $product)
                                <div class="col-12 col-md-4 mt-3">
                                    <a href="/tienda/{{ str_replace(' ', '-', quitar_tildes($store->name)) }}/{{ quitar_tildes($product->name) }}" target="_blank">
                                        <div class="card card-store" style="height: 100%;">
                                            <div class="zoom-container">
                                                <img class="zoomed-image" src="{{ asset($product->image) }}">
                                            </div>
                                            <div class="card-body" style="padding-bottom: 4rem;">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                <p class="card-text" style="width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">{{ $product->description }}</p>
                                                <a href="/tienda/{{ str_replace(' ', '-', quitar_tildes($store->name)) }}/{{ quitar_tildes($product->name) }}" target="_blank"
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
                                    <button class="btn btn btn-warning" id="load-products">Cargar más..</button>
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
                                                <p style="font-size: 2rem;">$<b>{{ $this->product_store->price }}</b></p>
                                                <p>Iva incluido</p>
                                                <div class="col-12 mt-3">
                                                    <h4 style="color: gray;">Cantidad disponible</h4>
                                                    <input type="number" class="form-control w-25 mt-3"
                                                        value="{{ $this->product_store->amount }}" disabled>
                                                </div>
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
                                            <div class="col-6">
                                                <p><b>Código:</b></p>
                                                <p>{{ $this->product_detail->code }}</p>
                                                <p><b>Referencia:</b></p>
                                                <p>{{ $this->product_detail->reference }}</p>
                                                <p><b>Detalle:</b></p>
                                                <p>{{ $this->product_detail->detail }}</p>
                                            </div>
                                            @if(isset($this->product_detail->promotion))
                                                <h5><b style="color: #6495ED">Producto en promoción</b></h5>
                                                <p>{{ $this->product_detail->promotion->description }}</p>
                                                <p><b>{{ $this->product_detail->promotion->price }}</b> de descuento</p>
                                            @endif
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
                                        if($subscription->user == null){
                                            continue;
                                        }
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
                @if (!$condition2)
                    <div class="tab-pane fade" id="promotions" role="tabpanel" aria-labelledby="promo">
                        <div class="row" style="border-bottom: solid 0.15rem #dee2e6;padding: 1rem;">
                            <h4 class="ms-3">Productos de la tienda que se encuentran en promoción</h4>
                        </div>
                        <div class="row">
                            @foreach ($products_promotion as $product)
                                <div class="col-12 col-md-4 mt-3">
                                    <a href="/tienda/{{ str_replace(' ', '-', quitar_tildes($store->name)) }}/{{ quitar_tildes($product->name) }}">
                                        <div class="card card-store" style="height: 100%;">
                                            <div class="zoom-container">
                                                <img class="zoomed-image" src="{{ asset($product->image) }}">
                                            </div>
                                            <div class="card-body" style="padding-bottom: 4rem;">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                <p class="card-text" style="width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">{{ $product->description }}</p>
                                                <a href="/tienda/{{ str_replace(' ', '-', quitar_tildes($store->name)) }}/{{ quitar_tildes($product->name) }}"
                                                    class="btn btn-warning position-absolute bottom-0 end-0"
                                                    style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-2" style="height: auto;margin-bottom: 3rem;">
            <div class="row" id="publicities">
                @foreach ($publicities as $key)
                    <li class="slide"
                        style="margin-top: .5rem;border-radius: 15px;background: transparent;border: transparent;">
                        <div class="card" style="max-height: 5rem;">
                            <div class="card-body" style="padding: 0rem;">
                                <div class="contenedor-imagen" onclick="goPagePublicity({{ $key->id }})"
                                    style="position: relative;
                                    display: inline-block;
                                    overflow: hidden;
                                    width: 100%;
                                    height: 100%;">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar datos de la empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @livewire('update-store', ['store' => $global_store, 'condition2' => $condition2]);
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
    @if (session('welcome_modal_shown'))
        <!-- Modal de bienvenida -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: cornflowerblue;color: white">
                        <h5 class="modal-title" id="welcomeModalLabel">Estimad@ {{ Auth::user()->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                            Es un placer darte la Bienvenida a Tulobuscas. Nos complace informarte que tu empresa ha
                            completado con éxito todo el proceso de registro en nuestro sistema.</p>
                        <p
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                            A partir de ahora, comenzarás a disfrutar de los numerosos beneficios que Tulobuscas te
                            ofrece. Nuestro objetivo es proporcionarte las herramientas y recursos necesarios para
                            impulsar tu empresa.</p>
                        <p
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                            Además, nos complace ofrecerte 30 días de servicio gratuito como parte de nuestro
                            recibimiento. Tendrás acceso completo a todas nuestras características y servicios.
                            Esperamos que explores y descubras todo lo que Tulobuscas tiene para ofrecer.</p>
                        <p
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                            Si tienes alguna pregunta o necesitas asistencia no dudes en ponerte en contacto con
                            nosotros. Estamos aquí para ayudarte.</p>
                        <p
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                            Reiteramos nuestra calurosa bienvenida a Tulobuscas. Estamos entusiasmados y esperamos con
                            interés la oportunidad de colaborarte y crecer juntos.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

@if (session('welcome_modal_shown'))
    <!-- Script para mostrar automáticamente el modal -->
    <script>
        $(document).ready(function() {
            $('#welcomeModal').modal('show');
        });
    </script>

    <?php
    // Eliminar la variable de sesión 'welcome_modal_shown'
    Illuminate\Support\Facades\Session::forget('welcome_modal_shown');
    ?>
@endif


<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                            var imageAd = `{{asset('${ad.image}')}}`;
                            if(imageAd.includes('//storage')){
                                imageAd = imageAd.replaceAll('//storage','/storage');
                            } 
                            $('#publicities').append(`<li class="slide"
                                style="margin-top: .5rem;border-radius: 15px;background: transparent;border: transparent;">
                                <div class="card" style="max-height: 5rem;">
                                    <div class="card-body" style="padding: 0rem;">
                                        <div class="contenedor-imagen" onclick="goPagePublicity(${ad.id})"
                                            style="position: relative;
                                            display: inline-block;
                                            overflow: hidden;
                                            width: 100%;
                                            height: 100%;">
                                            <img src="${imageAd}" class="img-fluid imagen-zoom">
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
                                var imageAd = `{{asset('${producto.image}')}}`;
                                if(imageAd.includes('//storage')){
                                    imageAd = imageAd.replaceAll('//storage','/storage');
                                } 
                                $('#productos-container').append(
                                    `<div class="col-12 col-md-4 mt-3"><a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/${producto.link}" target="_blank"><div class="card card-store" style="height: 100%;"><div class="zoom-container"><img class="zoomed-image" src="${imageAd}"></div><div class="card-body" style="padding-bottom: 4rem;"><h5 class="card-title">${producto.name}</h5><p class="card-text" style="width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">${producto.description}</p><a href="/tienda/{{ str_replace(' ', '-', $store->name) }}/${producto.link}" target="_blank" class="btn btn-warning position-absolute bottom-0 end-0" style="margin: .5rem;cursor: pointer;">Ver</a></div></div></a></div>`
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

    function searchDataStore() {
        $("search-data-store").submit();
    }

    function subirImagen(event, type) {
        const image = event.target.files[0];
        if (image) {
            const formData = new FormData();
            formData.append('file', image);
            formData.append('type', type);
            formData.append('stores_id', '{{ $store->id }}');
            $.ajax({
                url: "{{ route('upload-image-store') }}", // Reemplaza 'tu/controlador/ruta' con la ruta adecuada a tu controlador Laravel
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
                },
                error: function(xhr) {
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

    var myDropzone53 = new Dropzone("#myDropzone53", {
        url: "{{ route('renovation.store') }}",  // URL de destino
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
        },
        dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 1)`,
        dictMaxFilesExceeded: "No puedes subir más archivos",
        dictCancelUpload: "Cancelar subida",
        dictInvalidFileType: "No puedes subir archivos de este tipo",
        dictRemoveFile: "Remover archivo",
        acceptedFiles: 'image/*',
        maxFiles: 1,
        maxFilesize: 2.048,
        dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.",
        autoProcessQueue: false,
        addRemoveLinks: true,
        parallelUploads: 5,
        init: function() {
            var dropzone = this;

            // Adjuntar datos adicionales en el evento "sending"
            this.on("sending", function(file, xhr, formData) {
                var selectedPlan = document.querySelector('input[name="plan"]:checked');
                var concepto = document.querySelector('input[placeholder="Por favor ingrese un concepto"]').value;
                formData.append("plan_id", selectedPlan.value);    // Agregar el plan_id
                formData.append("comentary", concepto);
            });

            // Acción después de una subida exitosa
            this.on("success", function(file, response) {
                Swal.fire({
                    title: 'Renovación guardada.',
                    text: 'Tu renovación ahora esta pendiente de aprobación.',
                    icon: 'success',
                    confirmButtonText: 'Cerrar',
                    customClass: {
                        confirmButton: 'btn-confirm'
                    },
                }).then(function() {
                    location.reload(); // Recargar la página
                });
            });
        }
    });

    // Acción al hacer clic en "Guardar"
    $("#saveButton").click(() => {
        // Obtener los valores del formulario
        var selectedPlan = document.querySelector('input[name="plan"]:checked');
        var concepto = document.querySelector('input[placeholder="Por favor ingrese un concepto"]').value;
        var dropzone = myDropzone53;

        // Validar que el plan esté seleccionado
        if (!selectedPlan) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor seleccione un plan.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        // Validar que el campo concepto no esté vacío
        if (!concepto) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor ingrese un concepto.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        // Validar que haya al menos una imagen subida
        if (dropzone.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor suba al menos una imagen.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        // Si todas las validaciones pasan, procesar la subida
        dropzone.processQueue();
    });


</script>
