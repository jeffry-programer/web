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
            <label for="name" class="pb-3">{{ __('Ciudad') }}</label>
            <input type="hidden" name="cities_id" wire:model="city_id" id="city_store_data_id">
            <div class="autocomplete">
                <input class="form-select" type="text" id="myInput4" placeholder="Busca y selecciona una ciudad...">
                <ul id="myUL4">
                    @foreach ($dataCities as $city) 
                        <li><a onclick="seleccionarCiudad({{$city->id}})" wire:click="selectCity({{ $city->id }})">{{$city->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary" id="btn-save-ubi" disabled>Guardar</button>
    </div>
</div>