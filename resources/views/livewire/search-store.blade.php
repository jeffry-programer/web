<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tiendas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cleanData()"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="country" class="pb-3">{{ __('Estado') }}</label>
                <select class="form-select" wire:model="selectedState" wire:change="changeState()">
                    <option value="" selected>Seleccione un estado</option>
                    @foreach ($states as $index => $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Municipio') }}</label>
                <select class="form-select" wire:model="selectedMunicipality" wire:change="changeMunicipality()">
                    <option value="" selected>Seleccione un municipio</option>
                    @foreach ($municipalities as $index => $municipalitiy)
                        <option value="{{ $municipalitiy->id }}">{{ $municipalitiy->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Sector') }}</label>
                <select class="form-select" wire:model="selectedSector" wire:change="changeSector()">
                    <option selected>Seleccione un sector</option>
                    <option value="Todos">Todos</option>
                    @foreach ($sectors as $index => $sector)
                        <option value="{{ $sector->id }}">{{ $sector->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Nombre (opcional)') }}</label>
                <input type="text" wire:model="name_store" class="form-control" placeholder="Ingrese un nombre">
            </div>
        </div>
        <div class="row">
            <div>
                @if ($new_message)
                    <div class="alert alert-info mt-3">
                        No hemos encontrado resultados que coincidieran con tu busqueda, aqui puedes ver otros
                        resultados en
                        tu mismo municipio.
                    </div>
                @endif
                @if ($new_message2)
                    <div class="alert alert-info mt-3">
                        No hemos encontrado resultados que coincidieran con tu busqueda, aqui puedes ver otros
                        resultados en
                        tu mismo estado.
                    </div>
                @endif
                @if ($new_message3)
                    <div class="alert alert-info mt-3">
                        No hemos encontrado resultados que coincidieran con tu busqueda, aqui puedes ver otros
                        resultados en
                        tu mismo pais.
                    </div>
                @endif
            </div>
            @foreach ($data_stores as $store)
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
                                <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 18rem;"><i
                                        class="fa-solid fa-location-dot me-1"></i>{{ $store->municipality->name }} -
                                    {{ $store->address }}</p>
                                <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}"
                                    class="btn btn-warning position-absolute bottom-0 end-0"
                                    style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            @if ($empty_stores)
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            wire:click="cleanData()">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-store"
            @if ($disabled) disabled @endif wire:click="searchStore">Buscar</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
