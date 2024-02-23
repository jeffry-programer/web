<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
    integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>

</style>

<div>
    <div class="modal-body">
        <div>
            <div class="alert alert-info">Puedes asociar un producto ya existente o crear uno nuevo</div>
        </div>
        <form id="form" style="display: contents;">
            <input type="hidden" name="image" value="">
            <input type="hidden" name="count" value="0">
            <input type="hidden" name="link" value="0">
            <div class="row">
                <div class="col-12 form-group products">
                    <label>Nombre del producto</label>
                    <input type="hidden" name="cities_id" id="city_store_data_id">
                    <div class="autocomplete">
                        <input class="form-select mt-3" type="text" id="myInput30" name="name" placeholder="Busca y selecciona un producto...">
                        <ul id="myUL30">
                            @foreach ($products as $product) 
                                <li><a onclick="selectCity30({{ $product->id }})">{{$product->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row d-none nextSteep">
                <div class="col-md-6 form-group">
                    <label>Sub categoria</label>
                    <select class="form-select my-3" name="sub_categories_id">
                        @foreach ($sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Cilindraje</label>
                    <select class="form-select my-3" name="cylinder_capacities_id">
                        @foreach ($cylinder_capacities as $cylinder_capacity)
                            <option value="{{ $cylinder_capacity->id }}">{{ $cylinder_capacity->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Modelo</label>
                    <select class="form-select my-3" name="models_id">
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Caja</label>
                    <select class="form-select my-3" name="boxes_id">
                        @foreach ($boxes as $box)
                            <option value="{{ $box->id }}">{{ $box->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Tipo producto</label>
                    <select class="form-select my-3" name="type_products_id">
                        @foreach ($type_products as $type_product)
                            <option value="{{ $type_product->id }}">{{ $box->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Marca</label>
                    <select class="form-select my-3" name="brands_id">
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Descripción</label>
                    <input type="form-control" name="description" class="form-control my-3"
                        placeholder="Ingrese una descripción">
                </div>
                <div class="col-md-6 form-group">
                    <label>Código</label>
                    <input type="form-control" name="code" class="form-control my-3" placeholder="Ingrese un código">
                </div>
                <div class="col-md-6 form-group">
                    <label>Referencia</label>
                    <input type="form-control" name="reference" class="form-control my-3"
                        placeholder="Ingrese una referencia">
                </div>
                <div class="col-md-6 form-group">
                    <label>Detalle</label>
                    <input type="form-control" name="detail" class="form-control my-3"
                        placeholder="Ingrese un detalle">
                </div>
            </div>
            <div class="row d-none" id="product_store_row">
                <input type="hidden" name="stores_id" value="{{ $store->id }}">
                <input type="hidden" name="products_id" id="product_id">
                <div class="col-md-6 form-group">
                    <label>Cantidad</label>
                    <input type="number" name="amount" wire:model="amount" placeholder="Ingrese la cantidad"
                        class="form-control w-100 mt-3">
                </div>
                <div class="col-md-6 form-group">
                    <label>Precio</label>
                    <input type="number" name="price" wire:model="price" placeholder="Ingrese el precio"
                        class="form-control w-100 mt-3">
                </div>
            </div>
            <div class="row nextSteep d-none">
                <div class="col-md-12 form-group">
                    <label class="py-3" for="name">{{ __('Imagenes') }}</label>
                    <div class="dropzone" id="myDropzone24">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <input type="hidden" id="id_table">
    <input type="hidden" id="table" value="products">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="next">Siguiente</button>
        <button type="button" class="btn btn-primary d-none" id="store">Asociar</button>
    </div>
</div>

@section('js')
    <script>
        var ultimoValorSeleccionado;

        function reiniciarAutocompletado(){
          $(`#myUL30 li`).show(); // Mostrar todas las opciones
        }
      
        // Mostrar la lista al hacer clic en el input
        $(`#myInput30`).click(function() {
          $(`#myUL30`).show();
          reiniciarAutocompletado(); // Reiniciar autocompletado al hacer clic en el input
        });
        
        // Seleccionar una opción de la lista
        $(`#myUL30`).on("click", "li", function() {
          var value = $(this).text();
          $(`#myInput30`).val(value); // Colocar el valor seleccionado en el input
          ultimoValorSeleccionado = value; // Actualizar el último valor seleccionado
          $(`#myUL30`).hide(); // Ocultar la lista después de seleccionar
        });
        
        // Filtrar opciones según la entrada del usuario
        $(`#myInput30`).on("input", function() {
        reiniciarAutocompletado(); // Reiniciar autocompletado al escribir en el input
          var value = $(this).val().toLowerCase();
          $(`#myUL30 li`).each(function() {
            var text = $(this).text().toLowerCase();
            if (text.indexOf(value) > -1) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        });
        
        // Controlar clic fuera del área de autocompletado
        $(document).click(function(event) {
          var $target = $(event.target);
          var inputValue = $(`#myInput30`).val();
          if(!$target.closest('.autocomplete').length) {
            if (inputValue !== ultimoValorSeleccionado) {
              //$(`#myInput30`).val(""); // Vaciar el input si no se seleccionó una opción recientemente
            }
            $(`#myUL30`).hide(); // Ocultar la lista en cualquier caso
          }
        });

        $(document).ready(function() {
            $('#filtro').on('input', function() {
                var filtro = $(this).val().toLowerCase();
                $('#items option').each(function() {
                var textoItem = $(this).text().toLowerCase();
                if (textoItem.indexOf(filtro) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
                });
            });
        });

        function selectCity30(id){
            $("#product_id").val(id);
        }

        Dropzone.autoDiscover = false;

        var isset_images = false;

        var myDropzone = new Dropzone("#myDropzone24", {
            url: "{{route('imgs-store')}}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 5)`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize: 5,
            maxFiles: 5,
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("id", `${$("#id_table").val()}`);
                    formData.append("table", `${$("#table").val()}`);
                });

                this.on("success", function(file, response) {
                    if (file.status != 'success') {
                        return false;
                    }
                    if (this.getUploadingFiles().length === 0) {
                        isset_images = true;
                        hideAlertTime();
                    }
                });
            }
        });

        $("#next").click((e) => {
            if("#myInput30" == ""){
                Swal.fire({
                    title: "Por favor ingrese un nombre",
                    timer: 2000,
                    timerProgressBar: true,
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
                return false;
            }
            $(".products").addClass('d-none');
            $("#next").addClass('d-none');
            $("#store").removeClass('d-none');

            if($("#product_id").val() != ""){
                $("#product_store_row").removeClass('d-none');
            }else{
                $(".nextSteep").removeClass('d-none');
                $("#product_store_row").removeClass('d-none');
            }

        });

        $("#store").click((e) => {
            validateDataStore();
        });

        function validateDataStore(){
            showAlertTime();
            storeData();
        }

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

        function hideAlertTime() {
            setTimeout(() => {
                window.location.reload();
            }, 3000);

            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                title: "Registro agregado exitosamente",
                timer: 3000
            });
        }

        function showExistAlert(){
            setTimeout(() => {
                window.location.reload();
            }, 3000);

            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'info',
                showConfirmButton: false,
                title: "Este producto ya esta asociado a tu tienda",
                timer: 3000
            });
        }

        function storeData() {
            $.ajax({
                url: "{{ route('table-store-imgs-3') }}",
                data: $("#form").serialize(),
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                success(response) {
                    var res = JSON.parse(response);
                    if(res.includes('ok')){
                        hideAlertTime();
                        return false;
                    }else if(res.includes('exist')){
                        showExistAlert();
                        return false;
                    }
                    $("#id_table").val(res.split('-')[1]);
                    $("#table").val(res.split('-')[0]);
                    myDropzone.processQueue();
                    setTimeout(() => {
                        if (isset_images == false) {
                            hideAlertTime();
                        }
                    }, 3000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            Swal.fire({
                                title: value[0],
                                icon: "error",
                                timer: 2000,
                                timerProgressBar: true,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false
                            });
                        });
                    } else {
                        Swal.fire({
                            title: "Hubo un problema al procesar la solicitud",
                            icon: "error",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    </script>
@endsection
