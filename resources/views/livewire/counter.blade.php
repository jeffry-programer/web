<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                <label for="country" class="py-3">Estado<span
                        style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select wire:ignore class="form-select" wire:model="state_id" id="state" wire:change="changeState">
                    <option value="" selected>Seleccione un estado</option>
                    @foreach ($states as $index => $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 form-group">
                <label for="name" class="py-3">{{ __('Municipio') }}</label>
                <select class="form-select" wire:model="municipalities_id" id="municipality"
                    wire:change="changeMunicipality">
                    <option value="" selected>Seleccione un municipio</option>
                    @foreach ($municipalities as $municipality)
                        <option value="{{ $municipality->id }}" id="municipality-{{ $municipality->id }}"
                            data-name="{{ $municipality->name }}">{{ $municipality->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 form-group">
                <label for="country" class="py-3">Sectores<span
                        style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="sectors_id" wire:change="selectSector" id="sector">
                    <option value="" selected>Seleccione un sector</option>
                    <option value="Todos" selected>Todos</option>
                    @foreach ($sectors as $index => $sector)
                        <option value="{{ $sector->id }}">{{ $sector->description }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" id="city-search">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-save-ubi" @disabled($disabled)>Guardar</button>
    </div>
</div>