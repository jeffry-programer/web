<div>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner"
            style="background-image: url('{{ asset('images/1.jpg') }}');background-size: 100% 100%;background-repeat: no-repeat;">
            <div class="carousel-item active">
                <img src="{{ asset('images/2.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/3.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/4.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="row py-2" style="height: 5.5rem;">
        <div class="slide-option">
            <div id="infinite" class="highway-slider">
                <div class="container highway-barrier"
                    style="padding-bottom: 0rem;width: 100%;max-width: 99%;height: 4rem;">
                    <ul class="highway-lane">
                        @for ($i = 0; $i < 3; $i++)
                            @foreach ($publicities as $key)
                                <li class="highway-car">
                                    <div class="card">
                                        <div class="card-body" style="padding: 0rem;">
                                            <div class="contenedor-imagen"
                                                onclick="goPagePublicity({{ $key->id }})">
                                                <img src="{{ asset($key->image) }}" class="img-fluid imagen-zoom"
                                                    alt="Imagen 1">
                                                <div class="texto-encima">{{ $key->title }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if (count($stores) > 0)
        <div class="alert alert-secondary mx-3" role="alert">
            <h6 style="font-size: 1.2rem;margin-top: 0.5rem;">Tiendas con promociones</h6>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($stores as $store)
                    <div class="col-12 col-md-4 mt-3">
                        <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}">
                            <div class="card card-store">
                                <div class="zoom-container">
                                    <img class="zoomed-image" src="{{ asset($store->image) }}"
                                        alt="Descripción de la imagen">
                                </div>
                                <div class="card-body" style="padding-bottom: 4rem;">
                                    <h5 class="card-title">{{ $store->name }}</h5>
                                    <p class="card-text">{{ $store->description }}</p>
                                    <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;"><i
                                            class="fa-solid fa-location-dot me-1"></i>{{ $store->municipality->name }} - {{ $store->sector->description }} -
                                        {{ $store->address }}</p>
                                    <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}"
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
    @if (count($stores2) > 0)
        <div class="alert alert-secondary mx-3" role="alert">
            <h6 style="font-size: 1.2rem;margin-top: 0.5rem;">Tiendas más buscadas</h6>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($stores2 as $store)
                    <div class="col-12 col-md-4 mt-3">
                        <a href="/tienda/{{ str_replace(' ', '-', $store->store->name) }}">
                            <div class="card card-store">
                                <div class="zoom-container">
                                    <img class="zoomed-image" src="{{ asset($store->store->image) }}"
                                        alt="Descripción de la imagen">
                                </div>
                                <div class="card-body" style="padding-bottom: 4rem;">
                                    <h5 class="card-title">{{ $store->store->name }}</h5>
                                    <p class="card-text">{{ $store->store->description }}</p>
                                    <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">
                                        <i class="fa-solid fa-location-dot me-1"></i>{{ $store->store->municipality->name }} - {{ $store->store->sector->description }} -
                                        {{ $store->store->address }}
                                    </p>
                                    <a href="/tienda/{{ str_replace(' ', '-', $store->store->name) }}"
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
    @if (count($stores3) > 0)
        <div class="alert alert-secondary mx-3" role="alert">
            <h6 style="font-size: 1.2rem;margin-top: 0.5rem;">Busquedas recientes</h6>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($stores3 as $store)
                <div class="col-12 col-md-4 mt-3">
                    <a href="/tienda/{{ str_replace(' ', '-', $store->store->name) }}/{{ str_replace(' ', '-', $store->product->name) }}">
                        <div class="card card-store">
                            <div class="zoom-container">
                                <img class="zoomed-image" src="{{ asset($store->product->image) }}" alt="Descripción de la imagen">
                            </div>
                            <div class="card-body" style="padding-bottom: 4rem;">
                                <h5 class="card-title">{{ $store->product->name }}</h5>
                                <p class="card-text">{{ $store->product->description }}</p>
                                <a href="/tienda/{{ str_replace(' ', '-', $store->store->name) }}/{{ str_replace(' ', '-', $store->product->name) }}"
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
