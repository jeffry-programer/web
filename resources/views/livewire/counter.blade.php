<div>
    <div class="modal-body">
        {{--<div>    
            <div class="alert alert-info">
                Debes selecionar {{ $validation }} para guardar tu ubicación
            </div>
        </div>--}}
        <label for="">País</label>
        <select wire:change="updateCountry" wire:model="country" id="country" class="form-select my-3">
            <option selected>Seleccione un país</option>
            @foreach ($countries as $key)
                <option value="{{ $key->id }}">{{ $key->description }}</option>
            @endforeach
        </select>
        @if(!is_null($states))
            <label for="">Estado</label>
            <select wire:model="state" wire:change="updateState" id="state" class="form-select my-3" >
                <option selected>Seleccione un estado</option>
                @foreach ($states as $key)
                    <option value="{{ $key->id }}">{{ $key->name }}</option>
                @endforeach
            </select>  
        @endif
        @if(!is_null($cities))
        <label for="">Ciudad</label>
        <div class="autocompletar mt-3">
            <input class="form-control" placeholder="Ingrese y seleccione la ciudad" id="city" wire:model="cityInput" wire:keydown="search">
            <div id="tipo-mascota-lista-autocompletar" class="lista-autocompletar-items">
                @foreach ($dataCities as $key)
                    <div onclick="seleccionar('{{ $key->name }}', {{ $key->id }});"><strong>{{ substr($key->name, 0, strlen($cityInput)) }}</strong>{{ substr($key->name, strlen($cityInput)) }}</div>
                @endforeach
            </div>
        </div> 
        @endif
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary" id="btn-save-ubi" disabled>Guardar</button>
    </div>
</div>