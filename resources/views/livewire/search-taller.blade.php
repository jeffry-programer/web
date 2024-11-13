<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tiendas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="cleanData2()"></button>
    </div>
    <div class="modal-body">
        <div id="categories-container2" class="d-flex flex-wrap justify-content-center">
            @foreach ($categories as $category)
                <div class="category-card2" data-category-id="{{ $category->id }}">
                    <ion-icon name="{{ $category->icon }}" class="category-icon"></ion-icon>
                    <p class="category-description"><b>{{ $category->description }}</b></p>
                </div>
            @endforeach
        </div>
        
        <div id="filters-container2" style="display: none">
            <div class="row">
                <div class="col-md-12 form-group" style="display: flex;justify-content: center;align-items: center;">
                    <ion-icon id="btn-back-categories2" style="font-size: 4rem;width: 3rem;cursor: pointer;color: #0d6efd;" name="arrow-back-circle-outline"></ion-icon>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="state-search-store-id">{{ __('Estado') }}</label>
                    <select class="form-select" id="state-search-store-id2">
                        <option value="" selected>Seleccione un estado</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="municipality-search-store-id">{{ __('Municipio') }}</label>
                    <select class="form-select" id="municipality-search-store-id2">
                        <option value="" selected>Seleccione un municipio</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="sector-search-store-id">{{ __('Sector') }}</label>
                    <select class="form-select" id="sector-search-store-id2">
                        <option value="" selected>Seleccione un sector</option>
                        <option value="Todos">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="name-search-store">{{ __('Nombre (opcional)') }}</label>
                    <input type="text" id="name-search-store2" class="form-control" placeholder="Ingrese un nombre">
                </div>
            </div>
        </div>
        
        <div class="row" id="show-stores2">
            <div>
                <div class="alert alert-info mt-3" id="new_message_store_taller" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo municipio.
                </div>
                <div class="alert alert-info mt-3" id="new_message_store2_taller" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo estado.
                </div>
                <div class="alert alert-info mt-3" id="new_message_store3_taller" style="display: none">
                    No hemos encontrado resultados que coincidieran con tu búsqueda, aquí puedes ver otros
                    resultados en tu mismo país.
                </div>
            </div>
            
            <div class="row" id="showstore2">
                <!-- Aquí se mostrarán las tiendas -->
            </div>
            <div class="row d-none" id="loading-more-store2" style="margin-top: .5rem">
                <div class="col-12 text-center">
                    <button class="btn btn-warning" id="load-products-store2">Cargar más..</button>
                </div>
            </div>
            <div class="row" id="emptystore2" style="display: none">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
        onclick="cleanData2()">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-search-store2">Buscar</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        let selectedCategoryId2 = null;
        var loading2 = false;
        var page2 = 1;
        var hasMoreStores2 = true; // Variable para controlar si hay más tiendas disponibles

        $("#btn-search-store2").prop('disabled', true);
    
        // Al hacer clic en una categoría
        $('.category-card2').on('click', function () {
            selectedCategoryId2 = $(this).data('category-id');
            console.log(`Categoría seleccionada: ${selectedCategoryId2}`);
    
            // Ocultamos las categorías con animación
            $("#emptystore2").hide();
            $('#filters-container2').fadeIn("slow");
            $("#show-stores2").fadeIn("slow");
            $('#categories-container2').addClass('d-none');
        });

        $("#sector-search-store-id2").on('change', function () {
            if($(this).val() != ''){
                $("#btn-search-store2").prop('disabled', false);
            }
        });
    
    
        // Al hacer clic en el botón de regresar
        $('#btn-back-categories2').on('click', function () {
            console.log('Regresando a la selección de categorías');
            
            // Reseteamos los selects
            $('#state-search-store-id2').val('').trigger('change');
            $('#municipality-search-store-id2').html('<option value="" selected>Seleccione un municipio</option>');
            $('#sector-search-store-id2').html('<option value="" selected>Seleccione un sector</option><option value="Todos">Todos</option>');
            $('#name-search-store2').val(''); // Limpiar el input de nombre
            
            // Ocultar los filtros y mostrar las categorías
            $('#filters-container2').fadeOut("slow");

            $("#showstore2").html('');
            $("#loading-more-store2").addClass('d-none');
            $("#new_message_store_taller").hide();
            $("#new_message_store2_taller").hide();
            $("#new_message_store3_taller").hide();

            $("#emptystore2").fadeOut('slow');

            setTimeout(() => {
                $('#categories-container2').removeClass('d-none');
                $("#btn-search-store2").prop('disabled', true);
            }, 500);

            page2 = 1;
        });

        function cleanData2(){
            $('#state-search-store-id2').val('').trigger('change');
            $('#municipality-search-store-id2').html('<option value="" selected>Seleccione un municipio</option>');
            $('#sector-search-store-id2').html('<option value="" selected>Seleccione un sector</option><option value="Todos">Todos</option>');
            $('#name-search-store2').val(''); // Limpiar el input de nombre

            $("#showstore2").html('');
            $("#loading-more-store2").addClass('d-none');
            $("#new_message_store_taller").hide();
            $("#new_message_store2_taller").hide();
            $("#new_message_store3_taller").hide();

            $('#filters-container2').fadeOut("slow");
            
            setTimeout(() => {
                $("#emptystore2").hide();
                $("#btn-search-store2").prop('disabled', true);
                $('#categories-container2').removeClass('d-none');
            }, 500);
        }
    
        // Al seleccionar un estado, cargar los municipios
        $('#state-search-store-id2').on('change', function () {
            const stateId = $(this).val();
            if (!stateId) return;
    
            console.log(`Cargando municipios para el estado: ${stateId}`);
            $('#municipality-search-store-id2').html('<option>Cargando...</option>');
    
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
                    $('#municipality-search-store-id2').html(options);
                },
                error: function (xhr) {
                    console.error('Error al cargar municipios:', xhr.responseText);
                    alert('No se pudieron cargar los municipios. Inténtalo de nuevo.');
                }
            });
        });
    
        // Al seleccionar un municipio, cargar los sectores
        $('#municipality-search-store-id2').on('change', function () {
            const municipalityId = $(this).val();
            if (!municipalityId) return;
    
            console.log(`Cargando sectores para el municipio: ${municipalityId}`);
            $('#sector-search-store-id2').html('<option>Cargando...</option>');
    
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
                    $('#sector-search-store-id2').html(options);
                },
                error: function (xhr) {
                    console.error('Error al cargar sectores:', xhr.responseText);
                    alert('No se pudieron cargar los sectores. Inténtalo de nuevo.');
                }
            });
        });
    
        // Al hacer clic en buscar
        $("#btn-search-store2").click(() => {
            page2 = 1; // Reset the page2 number for a new search
            hasMoreStores2 = true;
            getDataStores2();
        });
    
        $("#load-products-store2").click(() => {
            getDataStores2();
        });
    
        function showAlertTime2() {
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
    
        function getDataStores2(){
            showAlertTime();
            if (!loading2 && hasMoreStores2){ // Verificar si hay más tiendas disponibles
                loading2 = true;
                $.ajax({
                    url: '/stores',
                    data: {
                        'selectedCategoryId' : selectedCategoryId2,
                        'selectedSector': $("#sector-search-store-id2").val(),
                        'selectedMunicipality': $("#municipality-search-store-id2").val(),
                        'selectedState': $("#state-search-store-id2").val(),
                        'name_store': $("#name-search-store2").val(),
                        'page': page2,
                        'type': 'Taller'
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    method: 'POST',
                    success: function(response) {
                        showMessage2(response); // Mostrar mensaje antes de cargar las tiendas
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
    
                            if (page2 === 1) {
                                $('#showstore2').html($html);
                            } else {
                                $('#showstore2').append($html);
                            }
                            $('#showstore2').show();
    
                            page2++;
                            hasMoreStores2 = response.has_more_stores; // Actualizar el estado de hasMoreStores
                        } else {
                            // Mostrar mensaje de no resultados
                            $('#showstore2').html('');
                            $('#emptystore2').fadeIn(3000);
                            setTimeout(() => {
                                $("#loading-more-store2").addClass('d-none');
                            }, 10);
                        }
    
                        // Mostrar u ocultar el botón "Cargar más"
                        if (hasMoreStores2) {
                            $("#loading-more-store2").removeClass('d-none');
                        } else {
                            $("#loading-more-store2").addClass('d-none');
                        }
    
                        loading2 = false;
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
    
        function showMessage2(response) {
            if (response.new_message) {
                $("#new_message_store_taller").fadeIn(1500);
                $("#new_message_store2_taller").hide();
                $("#new_message_store3_taller").hide();
                $("#emptystore2").hide();
            } else if (response.new_message2) {
                $("#new_message_store2_taller").fadeIn(1500);
                $("#new_message_store_taller").hide();
                $("#new_message_store3_taller").hide();
                $("#emptystore2").hide();
            } else if (response.new_message3) {
                $("#new_message_store3_taller").fadeIn(1500);
                $("#new_message_store2_taller").hide();
                $("#new_message_store_taller").hide();
                $("#emptystore2").hide();
            } else if (response.empty_stores) {
                $("#emptystore2").fadeIn(1500);
                $("#new_message_store3_taller").hide();
                $("#new_message_store2_taller").hide();
                $("#new_message_store_taller").hide();
            } else {
                $("#new_message_store_taller").hide();
                $("#new_message_store2_taller").hide();
                $("#new_message_store3_taller").hide();
                $("#emptystore2").hide();
            }
        }
    </script>    
    
</div>
