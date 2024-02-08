<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="country" class="pb-3">País<span style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="country_id" wire:change="search">
                    <option value="" selected>Seleccione un país</option>
                    @foreach ($countries as $index => $country)
                        <option value="{{ $country->id }}">{{ $country->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="name" class="pb-3">{{ __('Ciudad') }}</label>
                <input type="hidden" name="cities_id" wire:model="city_id" id="city_store_data_id">
                <div class="autocomplete">
                    <input class="form-select" type="text" id="myInput" placeholder="Busca y selecciona una ciudad...">
                    <ul id="myUL">
                        @foreach ($dataCities as $city) 
                            <li><a wire:click="selectCity({{ $city->id }})">{{$city->name}}</a></li>
                        @endforeach
                    </ul>
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
