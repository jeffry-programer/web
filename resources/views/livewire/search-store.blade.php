<div>
    <div>
        <div class="alert alert-info">
            Para poder buscar las tiendas debes ingresar un pais, escribir y seleccionar una ciudad
        </div>
    </div>
    <div class="modal-body">
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
                <input type="text" class="form-control" placeholder="Por favor ingrese un nombre">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-store" @if($disabled) disabled @endif>Buscar</button>
    </div>
</div>
