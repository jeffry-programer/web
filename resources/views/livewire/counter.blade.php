<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                <label for="country" class="pb-3">País<span style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="country_id" id="country" wire:change="changeCountry">
                    <option value="" selected>Seleccione un país</option>
                    @foreach ($countries as $index => $country)
                        <option value="{{ $country->id }}">{{ $country->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 form-group">
                <label for="country" class="py-3">Estado<span style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="state_id" id="state" wire:change="changeState">
                    <option value="" selected>Seleccione un estado</option>
                    @foreach ($states as $index => $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 form-group">
                <label for="name" class="py-3">{{ __('Ciudad') }}</label>
                <input type="hidden" name="cities_id" wire:model="city_id" id="city_store_data_id">
                <div class="autocomplete">
                    <input class="form-select" type="text" id="myInput6" placeholder="Busca y selecciona una ciudad...">
                    <ul id="myUL6">
                        @foreach ($dataCities as $city) 
                            <li><a onclick="selectCity({{ $city->id }})">{{$city->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="city-search">
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary" id="btn-save-ubi" @if($disabled) disabled @endif>Guardar</button>
    </div>
</div>