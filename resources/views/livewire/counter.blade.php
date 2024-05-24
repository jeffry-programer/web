<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                <label for="country" class="py-3">Estado<span
                        style="color: red;
                    margin-left: .2rem;">*</span></label>
                <select class="form-select" wire:model="state_id" id="state" wire:change="changeState">
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

@section('js')
    <script>
        function selectCity(id) {
            $("#city-search").val(id);
        }

        //Guardar ubicación
        $("#btn-save-ubi").click(() => {
            var nameMunicipality = $(`#municipality-${$("#municipality").val()}`).text();
            var sectorId = $("#sector").val();
            var municipalityId = $("#municipality").val();
            var stateId = $("#state").val();

            localStorage.setItem("name_municipality", nameMunicipality);
            localStorage.setItem("id_sector", sectorId);
            localStorage.setItem("id_municipality", municipalityId);
            localStorage.setItem("id_states", stateId);

            $("#value-state").val(stateId);
            $("#value-municipality").val(municipalityId);
            $("#value-sector").val(sectorId);

            $("#btn-ubi").html(`${nameMunicipality}`);
            $("#btn-save-ubi").attr('disabled', true);
            $("#exampleModal").modal('hide');
        });

        $(document).ready(() => {
            var nameMunicipality = localStorage.getItem('name_municipality');
            if (nameMunicipality !== null) {
                var sectorId = localStorage.getItem('id_sector');
                var municipalityId = localStorage.getItem('id_municipality');
                var stateId = localStorage.getItem('id_states');

                $("#btn-ubi").html(`${nameMunicipality}`);
                $.ajax({
                    url: `/update-counter-component?stateId=${stateId}&municipalityId=${municipalityId}&sectorId=${sectorId}`,
                    type: 'GET',
                    success: function(data){
                        var plantilla = '<option>Seleccione una opción</option>';
                        data.states.forEach(element => {
                            plantilla += `<option value="${element.id}">${element.name}</option>`;
                        });
                        $("#state").html(plantilla);
                        $("#state").show();
                        $("#state").val(stateId);
                        var plantilla = '<option>Seleccione una opción</option>';
                        data.municipalities.forEach(element => {
                            plantilla += `<option value="${element.id}">${element.name}</option>`;
                        });
                        $("#municipality").html(plantilla);
                        $("#municipality").show();
                        $("#municipality").val(municipalityId);
                        var plantilla = '<option>Seleccione una opción</option>';
                        data.sectors.forEach(element => {
                            plantilla += `<option value="${element.id}">${element.description}</option>`;
                        });
                        $("#sector").html(plantilla);
                        $("#sector").show();
                        $("#sector").val(sectorId);
                    }
                });
            }
            var category = localStorage.getItem('categories_id');
            if (category !== null) {
                $("#select-search-categories").val(category);
            }
        });
    </script>
@endsection
