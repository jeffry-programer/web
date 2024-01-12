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
            <img src="{{ asset('http://127.0.0.1:8080'.$store->image) }}" class="d-block w-100 img-carrusel" alt="...">
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
    <a href="https://api.whatsapp.com/send?phone={{$store->phone}}" class="whatsapp-btn" target="_blank" style="background: #28e26d;border-radius: 20%;width: 1rem;font-size: 3rem;">
        <i class="fa-brands fa-whatsapp" style="position: fixed;
        bottom: 3rem;
        background: #28e26d;
        border-radius: 100%;
        padding: .6rem;
        right: 14rem;
        cursor: pointer;"></i>    
    </a>
    <div class="row mt-3">
        <h2>{{ $store->name }}</h2>
        <div class="col-12 col-lg-10">
            <ul class="nav nav-tabs product-details-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2 @if($product_detail == null) active @endif" id="productEspecification" data-bs-toggle="tab" data-bs-target="#especificationProduct" type="button" role="tab" aria-controls="especificationProduct" aria-selected="true">Información de la tienda</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="productDescription" data-bs-toggle="tab" data-bs-target="#descriptionProduct" type="button" role="tab" aria-controls="descriptionProduct" aria-selected="false">Productos</button>
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
                <div class="tab-pane fade @if($product_detail == null) active show @endif" id="especificationProduct" role="tabpanel" aria-labelledby="productEspecification">
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
                                    <img src="http://127.0.0.1:8080{{$store->image}}" class="img-fluid" alt="Imagen Principal">
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
                                            <p><i class="fa-solid fa-location-dot me-2"></i>{{$store->address}}</p>
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
                <div class="tab-pane fade" id="descriptionProduct" role="tabpanel" aria-labelledby="productDescription">
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
                                        <img src="{{ asset('http://127.0.0.1:8080'.$product->image) }}" class="card-img-top" alt="...">
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
                                                        <img class="thumbnail img-fluid" src="http://127.0.0.1:8080{{$product_detail->image}}" alt="Imagen 1" style="height: 100%;">
                                                    </div>
                                                </div>
                                              </div>
                                              @if(isset($product_detail->aditionalPictures))
                                                @foreach ($product_detail->aditionalPictures as $key)
                                                    <div class="col-12 mt-3">
                                                        <div class="card" style="width: 7rem;height: 5rem;">
                                                            <div class="card-body" style="padding: .5rem;">
                                                                <img class="thumbnail img-fluid" src="http://127.0.0.1:8080{{$key->image}}" alt="Imagen 1" style="object-fit: cover;width: 7rem;height: 4rem;">
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
                                                    <img id="imagenPrincipal" class="img-fluid" src="http://127.0.0.1:8080{{$product_detail->image}}" style="height: 24rem;cursor:zoom-in;width: 100%;" alt="Imagen Principal">
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="container" style="margin-top: 5rem;position: relative">
                                    <div id="lupa" class="d-none" style="position:absolute;top: 0rem;background: rgb(255, 255, 255);width: 20rem;height: 15rem;background-image: url('http://127.0.0.1:8080{{$product_detail->image}}');background-repeat: no-repeat;"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-outline-primary w-100 mb-3"  data-bs-toggle="modal" data-bs-target="#exampleModal3">Editar datos del producto</button>
                                            <h4 style="color: gray;">{{ $this->brand }}</h4>
                                            <h3>{{ $this->product_detail->name }}</h3>
                                            <p><b>Ref.{{ $this->product_store->price }}</b></p>
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
                            <div class="col-md-6 offset-md-3" style="border: solid 1px #dee2e6;border-top: solid 2px #606060;">
                                <div class="div">
                                    @foreach($subscriptions as $subscription)
                                        <div class="row py-2 mt-3" style="border-bottom: solid 1px #dee2e6;">
                                            <div class="col-2">
                                                <img src="{{asset($subscription->user->profile_photo_path)}}" class="img-fluid" style="border-radius: 100%;">
                                            </div>
                                            <div class="col-10 d-flex align-items-center">
                                                <p>{{ $subscription->user->name }} {{ $subscription->user->last_name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>            
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
                        </div>
                    </div>                
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
                        </div>
                    </div>                
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
                        </div>
                    </div>                
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
                        </div>
                    </div>                
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
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