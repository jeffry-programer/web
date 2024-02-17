<div>
    <div>
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div>
        @if(session()->has('associateProduct'))
            <div class="alert alert-success">
                {{ session('associateProduct') }}
            </div>
        @endif
    </div>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="{{ asset($store->image) }}" class="d-block w-100 img-carrusel" alt="...">
          </div>
        </div>
    </div>
    <div class="scan" id="qrcode"></div>
    <div class="row">
        <div class="col-md-1 offset-md-6 d-flex align-items-center mt-3">
            @if($subscribed)
                <button type="button" class="btn btn-subs" style="display: flex;align-items: center;" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Suscrito
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="cursor: pointer">
                    <li><a class="dropdown-item" wire:click="nullSubscribe">Anular</a></li>
                </ul>
            @else
                <button class="btn btn-subs" wire:click="subscribe">Suscribete</button>
            @endif
        </div>
        <div class="col-12 col-md-5">
            <?php
                $link_store = str_replace(' ', '-', $store->name);
            ?>
            <form action="/tienda/{{ $link_store }}" class="row mt-3" id="form-search" autocomplete="off">  
                <div class="col-4 d-none d-md-flex align-items-center justify-content-center">
                    <select id="select-search-categories" name="tBGZall1t5CCeUqrQOkM" class="select-search">
                        <option selected>Categoria</option>
                        @foreach ($categories as $key)
                            <option value="{{$key->id}}">{{$key->name}}</option>
                        @endforeach
                    </select>   
                </div>
                <div class="col-8 col-md-6 d-flex align-items-center justify-content-center">
                    <input class="input-search" name="product" placeholder="Busca y compara productos" type="text">
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-magnifying-glass icons-search pointer" onclick="searchData()"></i>
                    <i class="fa-solid fa-microphone icons-search pointer"></i>
                </div>
            </form>         
        </div>
    </div>
    <?php
        $link_whatssap = str_replace('04', '4', $store->phone);
    ?>
    <button style="position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background-color: #28e26d;
    color: white;
    border: none;
    padding: 5px 20px;
    cursor: pointer;
    font-size: 3rem;
    border-radius: 100%;" onclick="window.open('https://api.whatsapp.com/send?phone={{$link_whatssap}}', '_blank');"><i class="fa-brands fa-whatsapp"></i></button>
    <div class="row mt-3">
        <h2>{{ $store->name }}</h2>
        <div class="col-12 col-lg-10">
            <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2 @if($product_detail == null && $search_products == false) active @endif" id="productEspecification" data-bs-toggle="tab" data-bs-target="#especificationProduct" type="button" role="tab" aria-controls="especificationProduct" aria-selected="true">Información de la tienda</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($search_products == true) active @endif" id="productDescription" data-bs-toggle="tab" data-bs-target="#descriptionProduct" type="button" role="tab" aria-controls="descriptionProduct" aria-selected="false">Productos</button>
                </li>

                @if($product_detail != null)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="productDescription2" data-bs-toggle="tab" data-bs-target="#descriptionProduct2" type="button" role="tab" aria-controls="descriptionProduct2" aria-selected="false">Detalle del producto</button>
                    </li>
                @endif

                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2" id="subs1" data-bs-toggle="tab" data-bs-target="#subs" type="button" role="tab" aria-controls="subs" aria-selected="true">Suscripciones</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2" id="promos" data-bs-toggle="tab" data-bs-target="#promo" type="button" role="tab" aria-controls="promo" aria-selected="true">Promociones</button>
                </li>
            </ul>
            <div class="tab-content pt-3 product-details-tab-content" id="myTabContent">
                <div class="tab-pane fade @if($product_detail == null && $search_products == false) active show @endif" id="especificationProduct" role="tabpanel" aria-labelledby="productEspecification">
                    <div class="container">
                        <div>
                            @if (session()->has('messageUpdateStore'))
                                <div class="alert alert-success">
                                    {{ session('messageUpdateStore') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-8 d-flex justify-content-center">
                                <a href="#">
                                    <img src="{{ asset($store->image) }}" class="img-fluid" alt="Imagen Principal">
                                </a>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="container" style="margin-top: 5rem;">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-outline-primary w-100 mb-3"  data-bs-toggle="modal" data-bs-target="#exampleModal2">Editar datos de la tienda</button>
                                            <h3>{{ $store->name }}</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><b>Descripción:</b></p>
                                            <p>{{$store->description}}</p>
                                            <p><b>Dirección:</b></p>
                                            <p><i class="fa-solid fa-location-dot me-2"></i>{{$store->city->municipality->state->name}} - {{$store->city->name}} - {{$store->address}}</p>
                                            <p><b>Correo electronico:</b></p>
                                            <p>{{$store->email}}</p>
                                            <p><b>Numero de contacto:</b></p>
                                            <p>{{ $store->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if($search_products == true) active show @endif" id="descriptionProduct" role="tabpanel" aria-labelledby="productDescription">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <button class="btn btn-outline-primary w-100 mb-3"  data-bs-toggle="modal" data-bs-target="#exampleModal4"><i class="fa-solid fa-plus me-3"></i>Asociar producto</button>
                            </div>
                        </div>
                        <div class="row">
                            @if($showMessageNotFoundProducts)
                                <div class="alert alert-info">
                                    No hemos encontrado productos que coincidieran con tu busqueda
                                </div>
                            @endif
                            @foreach ($products as $product)
                            <div class="col-12 col-md-4 mt-3">
                                <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{$product->link}}">
                                    <div class="card card-store">
                                        <div class="zoom-container">
                                            <img class="zoomed-image" src="{{ asset($product->image) }}" alt="Descripción de la imagen">
                                        </div>
                                        <div class="card-body" style="padding-bottom: 4rem;">
                                        <h5 class="card-title">{{$product->name}}</h5>
                                        <p class="card-text">{{$product->description}}</p>
                                        <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{$product->link}}" class="btn btn-warning position-absolute bottom-0 end-0" style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                                    </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                            {{ $products->links('custom-pagination-links-view') }}
                        </div>
                    </div>
                </div>
                @if ($product_detail != null)
                <div class="tab-pane fade active show" id="descriptionProduct2" role="tabpanel" aria-labelledby="productDescription2">
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
                                                        <img class="thumbnail img-fluid" style="height: 4rem;
                                                        width: 100%;
                                                      " src="{{ asset($product_detail->image) }}" alt="Imagen 1" style="height: 100%;">
                                                    </div>
                                                </div>
                                              </div>
                                              @if(isset($product_detail->aditionalPictures))
                                                @foreach ($product_detail->aditionalPictures as $key)
                                                    <div class="col-12 mt-3">
                                                        <div class="card" style="width: 7rem;height: 5rem;">
                                                            <div class="card-body" style="padding: .5rem;">
                                                                <img class="thumbnail img-fluid" style="height: 4rem;
                                                                width: 100%;
                                                              " src="{{ asset($key->image) }}" alt="Imagen 1" style="object-fit: cover;width: 7rem;height: 4rem;">
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
                                                    <img id="imagenPrincipal" class="img-fluid" src="{{ asset($product_detail->image) }}" style="height: 24rem;cursor:zoom-in;width: 100%;" alt="Imagen Principal">
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="container" style="margin-top: 5rem;position: relative">
                                    <div id="lupa" class="d-none" style="position:absolute;top: 0rem;background: rgb(255, 255, 255);width: 20rem;height: 15rem;background-image: url('{{ asset($product_detail->image) }}');background-repeat: no-repeat;"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-outline-primary w-100 mb-3"  data-bs-toggle="modal" data-bs-target="#exampleModal3">Editar datos del producto</button>
                                            <h4 style="color: gray;">{{ $this->product_detail->brand->description }}</h4>
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
                                            <p>{{$this->product_detail->code}}</p>
                                            <p>{{$this->product_detail->reference}}</p>
                                            <p>{{$this->product_detail->detail}}</p>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <h4 style="color: gray;">Cantidad disponible</h4>
                                            <input type="number" class="form-control w-25 mt-3" value="{{ $this->product_store->amount }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-8">
                                <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link me-2 active" id="productEspecification2" data-bs-toggle="tab" data-bs-target="#especificationProduct2" type="button" role="tab" aria-controls="especificationProduct2" aria-selected="true">Descripción del producto</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3 product-details-tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="especificationProduct2" role="tabpanel" aria-labelledby="productEspecification2">
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
                        <h4>Suscripciones</h4>
                        <div class="row">
                            <div class="col-md-6 offset-md-3" style="border-top: solid 2px #606060;">
                                    <ul class="list-group">
                                        @foreach($subscriptions as $subscription)
                                                <?php
                                                    $url_profile = "";
                                                    if($subscription->user->image == null){
                                                        $letter = strtoupper($subscription->user->name[0]);
                                                        $ruta_imagen = "https://ui-avatars.com/api/?name=".$letter."&amp;color=7F9CF5&amp;background=EBF4FF";
                                                    }else{
                                                        $assets = asset('');
                                                        $ruta_imagen = $subscription->user->image;
                                                        if(!str_contains($ruta_imagen, 'storage')) $ruta_imagen = '/storage/'.$ruta_imagen;
                                                        if(str_contains($ruta_imagen, 'http://localhost/')) str_replace('http://localhost/', $assets, $ruta_imagen);
                                                    }
                                                ?>
                                            <a href="/tienda/Tiendita">
                                                <li class="list-group-item d-flex" style="justify-content: start;align-items: center;border: none;">
                                                    <img src="{{ $ruta_imagen }}" alt="img" style="width: 3rem;
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
                    @livewire('promotions', ['global_store' => $global_store])
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-2">
            <div class="row">
                <div class="slider-container" style="height: 50rem;">
                    <div class="slider-container">
                        <div class="slider">
                            @for ($i = 0; $i < 100; $i++)
                            @foreach ($publicities as $key)
                            <li class="slide" style="margin-top: .5rem;border-radius: 15px;">
                                <div class="card">
                                    <div class="card-body" style="padding: 0rem;">
                                      <div class="contenedor-imagen" onclick="goPagePublicity({{ $key->id }})">
                                        <img src="{{ asset($key->image) }}" class="img-fluid imagen-zoom" alt="Imagen 1">
                                        <div class="texto-encima">{{ $key->title }}</div>
                                      </div>
                                    </div>
                                  </div>
                            </li>
                            @endforeach
                          @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        @if($product_store !== null)
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar datos del producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @livewire('update-product', ['product_store' => $product_store, 'store' => $store, 'product_detail' => $product_detail]);
                </div>
            </div>
        </div>  
        @endif
        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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