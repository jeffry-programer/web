<div>
    <div class="modal-body">
        <div class="pb-3">
            <div class="alert alert-info">
                Para poder buscar las tiendas debes ingresar un pais, escribir y seleccionar una ciudad, el nombre de la tienda es opcional. 
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="country" class="pb-3">País<span style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="country_id">
                    <option value="" selected>Seleccione un país</option>
                    @foreach ($countries as $index => $country)
                        <option value="{{ $country->id }}">{{ $country->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="">Ciudad<span style="color: red;
                    margin-left: .2rem;">*</span></label>
                <div class="autocompletar mt-3">
                    <input class="form-control" placeholder="Ingrese y seleccione la ciudad" id="city" wire:model="cityInput" wire:keydown="search">
                    <div id="tipo-mascota-lista-autocompletar" class="lista-autocompletar-items">
                        @foreach ($dataCities as $key)
                            <div wire:click="seleccionar('{{ $key->name }}', {{ $key->id }});"><strong>{{ substr($key->name, 0, strlen($cityInput)) }}</strong>{{ substr($key->name, strlen($cityInput)) }}</div>
                        @endforeach
                    </div>
                </div> 
            </div>
            <div class="col-md-4 form-group">
                <label for="" class="pb-3">Nombre tienda</label>
                <input type="text" class="form-control" placeholder="Por favor ingrese un nombre" wire:model="name_store">
            </div>
        </div>
        <div class="row">
            @foreach ($data_stores as $store)
                <div class="col-12 col-md-4 mt-3">
                    <a href="/tienda/{{ str_replace(' ','-', $store->name) }}">
                    <div class="card card-store">
                        <div class="zoom-container">
                            <img class="zoomed-image" src="{{ asset($store->image) }}" alt="Descripción de la imagen">
                        </div>
                        <div class="card-body" style="padding-bottom: 4rem;">
                        <h5 class="card-title">{{$store->name}}</h5>
                        <p class="card-text">{{$store->description}}</p>
                        <p class="position-absolute bottom-0 start-0" style="padding: 1rem;"><i class="fa-solid fa-location-dot me-1"></i>{{$store->address}}</p>
                        <a href="/tienda/{{ str_replace(' ','-', $store->name) }}" class="btn btn-warning position-absolute bottom-0 end-0" style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                        </div>
                    </div>
                    </a>
                </div>
            @endforeach
            @if($empty_stores)
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h2 class="ms-5 mt-5">Ups no hemos encontrado resultados a tu búsqueda</h2>
                    </div>
                    <div class="col-12 col-md-6">
                        <img src="{{ asset('images/store.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-store" @if($disabled) disabled @endif wire:click="searchStore">Buscar</button>
    </div>
</div>
