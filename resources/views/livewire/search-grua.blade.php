<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Grúas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="cleanData3()"></button>
    </div>
    <div class="modal-body">
        <div id="categories-container3" class="d-flex flex-wrap justify-content-center">
            @foreach ($categories as $category)
                <div class="category-card3" data-category-id="{{ $category->id }}">
                    <ion-icon name="{{ $category->icon }}" class="category-icon"></ion-icon>
                    <p class="category-description"><b>{{ $category->description }}</b></p>
                </div>
            @endforeach
        </div>
        
        <div id="filters-container3" style="display: none">
            <div class="row">
                <div class="col-md-12 form-group" style="display: flex;justify-content: center;align-items: center;">
                    <ion-icon id="btn-back-categories3" style="font-size: 4rem;width: 3rem;cursor: pointer;color: #0d6efd;" name="arrow-back-circle-outline"></ion-icon>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="state-search-store-id">{{ __('Estado') }}</label>
                    <select class="form-select" id="state-search-store-id3">
                        <option value="" selected>Seleccione un estado</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="municipality-search-store-id">{{ __('Municipio') }}</label>
                    <select class="form-select" id="municipality-search-store-id3">
                        <option value="" selected>Seleccione un municipio</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="sector-search-store-id">{{ __('Sector') }}</label>
                    <select class="form-select" id="sector-search-store-id3">
                        <option value="" selected>Seleccione un sector</option>
                        <option value="Todos">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="name-search-store">{{ __('Nombre (opcional)') }}</label>
                    <input type="text" id="name-search-store3" class="form-control" placeholder="Ingrese un nombre">
                </div>
            </div>
        </div>
        
        <div class="row" id="show-stores3">
            <div>
                <div class="alert alert-info mt-3" id="new_message_store_grua" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo municipio.
                </div>
                <div class="alert alert-info mt-3" id="new_message_store3_grua" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo estado.
                </div>
                <div class="alert alert-info mt-3" id="new_message_store3_grua" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo país.
                </div>
            </div>
            
            <div class="row" id="showstore3">
                <!-- Aquí se mostrarán las tiendas -->
            </div>
            <div class="row d-none" id="loading-more-store3" style="margin-top: .5rem">
                <div class="col-12 text-center">
                    <button class="btn btn-warning" id="load-products-store3">Cargar más..</button>
                </div>
            </div>
            <div class="row" id="emptystore3" style="display: none">
                <div class="col-12 col-md-6">
                    <h3 class="ms-5 mt-5">Ups no hemos encontrado resultados a tu búsqueda</h3>
                </div>
                <div class="col-12 col-md-6">
                    <img src="{{ asset('images/store.png') }}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
        onclick="cleanData3()">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-store3">Buscar</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        let selectedCategoryId3 = null;
        var loading3 = false;
        var page3 = 1;
        var hasMoreStores3 = true; // Variable para controlar si hay más tiendas disponibles

        $("#btn-search-store3").prop('disabled', true);
    
        // Al hacer clic en una categoría
        $('.category-card3').on('click', function () {
            selectedCategoryId3 = $(this).data('category-id');
            console.log(`Categoría seleccionada: ${selectedCategoryId3}`);
    
            // Ocultamos las categorías con animación
            $("#emptystore3").hide();
            $('#filters-container3').fadeIn("slow");
            $("#show-stores3").fadeIn("slow");
            $('#categories-container3').addClass('d-none');
        });

        $("#sector-search-store-id3").on('change', function () {
            if($(this).val() != ''){
                $("#btn-search-store3").prop('disabled', false);
            }
        });
    
    
        // Al hacer clic en el botón de regresar
        $('#btn-back-categories3').on('click', function () {
            console.log('Regresando a la selección de categorías');
            
            // Reseteamos los selects
            $('#state-search-store-id3').val('').trigger('change');
            $('#municipality-search-store-id3').html('<option value="" selected>Seleccione un municipio</option>');
            $('#sector-search-store-id3').html('<option value="" selected>Seleccione un sector</option><option value="Todos">Todos</option>');
            $('#name-search-store3').val(''); // Limpiar el input de nombre
            
            // Ocultar los filtros y mostrar las categorías
            $('#filters-container3').fadeOut("slow");

            $("#showstore3").html('');
            $("#loading-more-store3").addClass('d-none');
            $("#new_message_store_grua").hide();
            $("#new_message_store3_grua").hide();
            $("#new_message_store3_grua").hide();

            $("#emptystore3").fadeOut('slow');

            setTimeout(() => {
                $('#categories-container3').removeClass('d-none');
                $("#btn-search-store3").prop('disabled', true);
            }, 500);

            page3 = 1;
        });

        function cleanData3(){
            $('#state-search-store-id3').val('').trigger('change');
            $('#municipality-search-store-id3').html('<option value="" selected>Seleccione un municipio</option>');
            $('#sector-search-store-id3').html('<option value="" selected>Seleccione un sector</option><option value="Todos">Todos</option>');
            $('#name-search-store3').val(''); // Limpiar el input de nombre

            $("#showstore3").html('');
            $("#loading-more-store3").addClass('d-none');
            $("#new_message_store_grua").hide();
            $("#new_message_store3_grua").hide();
            $("#new_message_store3_grua").hide();

            $('#filters-container3').fadeOut("slow");
            setTimeout(() => {
                $("#emptystore3").hide();
                $("#btn-search-store3").prop('disabled', true);
                $('#categories-container3').removeClass('d-none');
            }, 500);
        }
    
        // Al seleccionar un estado, cargar los municipios
        $('#state-search-store-id3').on('change', function () {
            const stateId = $(this).val();
            if (!stateId) return;
    
            console.log(`Cargando municipios para el estado: ${stateId}`);
            $('#municipality-search-store-id3').html('<option>Cargando...</option>');
    
            $.ajax({
                url: '/get-municipalities',
                method: 'POST',
                data: { stateId },
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                success: function (municipalities) {
                    console.log('Municipios cargados:', municipalities);
                    let options = '<option value="">Seleccione un municipio</option>';
                    municipalities.forEach(municipality => {
                        options += `<option value="${municipality.id}">${municipality.name}</option>`;
                    });
                    $('#municipality-search-store-id3').html(options);
                },
                error: function (xhr) {
                    console.error('Error al cargar municipios:', xhr.responseText);
                    alert('No se pudieron cargar los municipios. Inténtalo de nuevo.');
                }
            });
        });
    
        // Al seleccionar un municipio, cargar los sectores
        $('#municipality-search-store-id3').on('change', function () {
            const municipalityId = $(this).val();
            if (!municipalityId) return;
    
            console.log(`Cargando sectores para el municipio: ${municipalityId}`);
            $('#sector-search-store-id3').html('<option>Cargando...</option>');
    
            $.ajax({
                url: '/get-sectors',
                method: 'POST',
                data: { municipalityId },
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                success: function (sectors) {
                    console.log('Sectores cargados:', sectors);
                    let options = '<option value="">Seleccione un sector</option><option value="Todos">Todos</option>';
                    sectors.forEach(sector => {
                        options += `<option value="${sector.id}">${sector.description}</option>`;
                    });
                    $('#sector-search-store-id3').html(options);
                },
                error: function (xhr) {
                    console.error('Error al cargar sectores:', xhr.responseText);
                    alert('No se pudieron cargar los sectores. Inténtalo de nuevo.');
                }
            });
        });
    
        // Al hacer clic en buscar
        $("#btn-search-store3").click(() => {
            page3 = 1; // Reset the page3 number for a new search
            hasMoreStores3 = true;
            getDataStores3();
        });
    
        $("#load-products-store3").click(() => {
            getDataStores3();
        });
    
        function showAlertTime3() {
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
    
        function getDataStores3(){
            showAlertTime();
            if (!loading3 && hasMoreStores3){ // Verificar si hay más tiendas disponibles
                loading3 = true;
                $.ajax({
                    url: '/stores',
                    data: {
                        'selectedCategoryId' : selectedCategoryId3,
                        'selectedSector': $("#sector-search-store-id3").val(),
                        'selectedMunicipality': $("#municipality-search-store-id3").val(),
                        'selectedState': $("#state-search-store-id3").val(),
                        'name_store': $("#name-search-store3").val(),
                        'page': page3,
                        'type': 'Grua'
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    method: 'POST',
                    success: function(response) {
                        showMessage3(response); // Mostrar mensaje antes de cargar las tiendas
                        var stores = response.stores.data;
                        if (stores.length > 0) {
                            var $html = "";
                            stores.forEach(function(store) {
                                if(store.image == null || store.image == ''){
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
                                                            <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;">
                                                                <i class="fa-solid fa-location-dot me-1"></i>${store.municipality.name} - ${ store.sector.description } - ${store.address}
                                                            </p>
                                                            <a href="/tienda/${storeName}" class="btn btn-warning position-absolute bottom-0 end-0" style="margin: .5rem;cursor: pointer;">Ver</a>
                                                        </section>
                                                    </section>
                                                </a>
                                            </section>`;
                            });
    
                            if (page3 === 1) {
                                $('#showstore3').html($html);
                            } else {
                                $('#showstore3').append($html);
                            }
                            $('#showstore3').show();
    
                            page3++;
                            hasMoreStores3 = response.has_more_stores; // Actualizar el estado de hasMoreStores
                        } else {
                            // Mostrar mensaje de no resultados
                            $('#showstore3').html('');
                            $('#emptystore3').fadeIn(3000);
                            setTimeout(() => {
                                $("#loading-more-store3").addClass('d-none');
                            }, 10);
                        }
    
                        // Mostrar u ocultar el botón "Cargar más"
                        if (hasMoreStores3) {
                            $("#loading-more-store3").removeClass('d-none');
                        } else {
                            $("#loading-more-store3").addClass('d-none');
                        }
    
                        loading3 = false;
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
    
        function showMessage3(response) {
            if (response.new_message) {
                $("#new_message_store_grua").fadeIn(1500);
                $("#new_message_store3_grua").hide();
                $("#new_message_store3_grua").hide();
                $("#emptystore3").hide();
            } else if (response.new_message3) {
                $("#new_message_store3_grua").fadeIn(1500);
                $("#new_message_store_grua").hide();
                $("#new_message_store3_grua").hide();
                $("#emptystore3").hide();
            } else if (response.new_message3) {
                $("#new_message_store3_grua").fadeIn(1500);
                $("#new_message_store3_grua").hide();
                $("#new_message_store_grua").hide();
                $("#emptystore3").hide();
            } else if (response.empty_stores) {
                $("#emptystore3").fadeIn(1500);
                $("#new_message_store3_grua").hide();
                $("#new_message_store3_grua").hide();
                $("#new_message_store_grua").hide();
            } else {
                $("#new_message_store_grua").hide();
                $("#new_message_store3_grua").hide();
                $("#new_message_store3_grua").hide();
                $("#emptystore3").hide();
            }
        }
    </script>    
    
</div>
