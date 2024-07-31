<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Gruas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cleanData()"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="country" class="pb-3">{{ __('Estado') }}</label>
                <select class="form-select" id="state-search-grua-id" wire:model="selectedState" wire:change="changeState()">
                    <option value="" selected>Seleccione un estado</option>
                    @foreach ($states as $index => $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Municipio') }}</label>
                <select class="form-select" id="municipality-search-grua-id" wire:model="selectedMunicipality" wire:change="changeMunicipality()">
                    <option value="" selected>Seleccione un municipio</option>
                    @foreach ($municipalities as $index => $municipalitiy)
                    <option value="{{ $municipalitiy->id }}">{{ $municipalitiy->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Sector') }}</label>
                <select class="form-select" id="sector-search-grua-id" wire:model="selectedSector" wire:change="changeSector()">
                    <option selected value="">Seleccione un sector</option>
                    <option value="Todos">Todos</option>
                    @foreach ($sectors as $index => $sector)
                    <option value="{{ $sector->id }}">{{ $sector->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="name" class="pb-3">{{ __('Nombre (opcional)') }}</label>
                <input type="text" wire:model="name_store" id="name-search-grua" class="form-control" placeholder="Ingrese un nombre">
            </div>
        </div>
        <div class="row">
            <div>
                <div class="alert alert-info mt-3" id="new_message_grua" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo municipio.
                </div>
                <div class="alert alert-info mt-3" id="new_message_grua2" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo estado.
                </div>
                <div class="alert alert-info mt-3" id="new_message_grua3" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo país.
                </div>
            </div>
            <div class="row" id="showGruas">
                <!-- Aquí se mostrarán las tiendas -->
            </div>
            <div class="row d-none" id="loading-more-grua" style="margin-top: .5rem">
                <div class="col-12 text-center">
                    <button class="btn btn-warning" id="load-products-grua">Cargar más..</button>
                </div>
            </div>
            <div class="row" id="emptyGruas" style="display: none">
                <div class="col-12 col-md-6">
                    <h2 class="ms-5 mt-5">Ups no hemos encontrado resultados a tu búsqueda</h2>
                </div>
                <div class="col-12 col-md-6">
                    <img src="{{ asset('images/store.png') }}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="cleanData()">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-grua" @if ($disabled) disabled @endif>Buscar</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        var loading = false;
        var page = 1;
        var hasMoreStores = true; // Variable para controlar si hay más tiendas disponibles
    
        $("#btn-search-grua").click(() => {
            page = 1; // Reset the page number for a new search
            hasMoreStores = true;
            getDataGruas();
        });
    
        $("#load-products-grua").click(() => {
            getDataGruas();
        });
    
        function showAlertTime() {
            Swal.fire({
                toast: true,
                position: 'center',
                title: "Cargando por favor espere...",
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }
    
        function getDataGruas(){
            showAlertTime();
            if (!loading && hasMoreStores){ // Verificar si hay más tiendas disponibles
                loading = true;
                $.ajax({
                    url: '/stores',
                    data: {
                        'selectedSector': $("#sector-search-grua-id").val(),
                        'selectedMunicipality': $("#municipality-search-grua-id").val(),
                        'selectedState': $("#state-search-grua-id").val(),
                        'name_store': $("#name-search-grua").val(),
                        'page': page,
                        'type': @json($type_store)
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    method: 'POST',
                    success: function(response) {
                        showMessageGrua(response); // Mostrar mensaje antes de cargar las tiendas
                        var stores = response.stores.data;
                        if (stores.length > 0) {
                            var $html = "";
                            stores.forEach(function(store) {
                                if(store.image == null){
                                    var imageAd = `{{asset('/images/1.jpg')}}`;
                                }else{
                                    var imageAd = `{{asset('${store.image}')}}`;
                                }
                                if (imageAd.includes('//storage')) {
                                    imageAd = imageAd.replaceAll('//storage', '/storage');
                                }
                                var storeName = store.name.replaceAll(' ', '-');
                                $html += `<section class="col-12 col-md-4 mt-3">
                                                <a href="/tienda/${storeName}">
                                                    <section class="card card-store">
                                                        <section class="zoom-container">
                                                            <img class="zoomed-image" src="${imageAd}" alt="Descripción de la imagen">
                                                        </section>
                                                        <section class="card-body" style="padding-bottom: 4rem;">
                                                            <h5 class="card-title">${store.name}</h5>
                                                            <p class="card-text">${store.description}</p>
                                                            <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 80%;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">
                                                                <i class="fa-solid fa-location-dot me-1"></i>${store.municipality.name} - ${store.address}
                                                            </p>
                                                            <a href="/tienda/${storeName}" class="btn btn-warning position-absolute bottom-0 end-0" style="margin: .5rem;cursor: pointer;">Ver</a>
                                                        </section>
                                                    </section>
                                                </a>
                                            </section>`;
                            });
    
                            if (page === 1) {
                                $('#showGruas').html($html);
                            } else {
                                $('#showGruas').append($html);
                            }
                            $('#showGruas').show();
    
                            page++;
                            hasMoreStores = response.has_more_stores; // Actualizar el estado de hasMoreStores
                        } else {
                            // Mostrar mensaje de no resultados
                            $('#showGruas').html('');
                            $('#emptyGruas').fadeIn(3000);
                            setTimeout(() => {
                                $("#loading-more-grua").addClass('d-none');
                            }, 10);
                        }
    
                        // Mostrar u ocultar el botón "Cargar más"
                        if (hasMoreStores) {
                            $("#loading-more-grua").removeClass('d-none');
                        } else {
                            $("#loading-more-grua").addClass('d-none');
                        }
    
                        loading = false;
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                    complete: function() {
                        Swal.close(); // Asegúrate de cerrar el spinner incluso si hay un error
                    }
                });
            }
        }
    
        function showMessageGrua(response) {
            if (response.new_message) {
                $("#new_message_grua").fadeIn(1500);
                $("#new_message_grua2").hide();
                $("#new_message_grua3").hide();
                $("#emptyGruas").hide();
            } else if (response.new_message2) {
                $("#new_message_grua2").fadeIn(1500);
                $("#new_message_grua").hide();
                $("#new_message_grua3").hide();
                $("#emptyGruas").hide();
            } else if (response.new_message3) {
                $("#new_message_grua3").fadeIn(1500);
                $("#new_message_grua2").hide();
                $("#new_message_grua").hide();
                $("#emptyGruas").hide();
            } else if (response.empty_stores) {
                $("#emptyGruas").fadeIn(1500);
                $("#new_message_grua3").hide();
                $("#new_message_grua2").hide();
                $("#new_message_grua").hide();
            } else {
                $("#new_message_grua").hide();
                $("#new_message_grua2").hide();
                $("#new_message_grua3").hide();
                $("#emptyGruas").hide();
            }
        }
    </script>    
</div>
