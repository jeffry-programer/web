<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
    integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />


<style>
    .dropzone .dz-preview.dz-error:hover .dz-error-message {
        top: .3rem !important;
    }
</style>

<div>
    <div class="modal-body">
        <form id="form2">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nombre de la tienda</label>
                    <input type="text" class="form-control mt-3 mb-3" name="name" value="{{$store->name}}" placeholder="Ingrese un nombre">
                    <label>Dirección</label>
                    <input type="text" class="form-control mt-3 mb-3" name="address" value="{{$store->address}}" placeholder="Ingrese una dirección">
                    <label>Descripción</label>
                    <input type="text" class="form-control mt-3 mb-3" name="description" value="{{$store->description}}" placeholder="Ingrese una descripción">
                </div>
                <div class="col-md-6 form-group">
                    <label>Correo electronico</label>
                    <input type="email" class="form-control mt-3 mb-3" name="email" value="{{$store->email}}" placeholder="Ingrese un correo">
                    <label>Numeros de contacto</label>
                    <input type="number" class="form-control mt-3" name="phone" value="{{$store->phone}}" placeholder="Ingrese un numero">
                    <label class="py-3" for="name">{{ __('Imagenes') }}</label>
                    <div class="dropzone" id="myDropzone25"></div>
                </div>
                <input type="hidden" name="stores_id" value="{{$store->id}}">
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="update">Guardar</button>
    </div>
</div>

@section('js')
<script>
    Dropzone.autoDiscover = false;

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
            maxFilesize: 2.048,
            maxFiles: 5,
            autoProcessQueue: false,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
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
            if($("#myInput30").val() == ""){
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

    $("#update").click((e) => {
        validateDataUpdate();
    });

    var isset_images2 = false;

    var myDropzone2 = new Dropzone("#myDropzone25", {
            url: "{{route('imgs-store')}}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize: 2.048,
            maxFiles: 5,
            autoProcessQueue: false,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("id", "{{$store->id}}");
                    formData.append("table", 'stores');
                });

                this.on("success", function(file, response) {
                    if (file.status != 'success') {
                        return false;
                    }
                    if (this.getUploadingFiles().length === 0) {
                        isset_images2 = true;
                        hideAlertTime2();
                    }
                });

                this.on("error", function(file, xhr) {
                    console.log(xhr.status);
                    
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
                });
            }
        });

        var isset_images3 = false;

        var myDropzone3 = new Dropzone("#myDropzone82", { 
            url: "{{route('imgs-store-data')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 1)`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize: 2.048,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido    maxFiles: 2,
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function(){
                this.on("sending", function(file, xhr, formData){
                    formData.append("id", $("#id_promotion_save").val());
                    formData.append("table", `promotions`);
                });

                this.on("success", function(file, response) {
                    if(file.status != 'success'){
                        return false;
                    }
                    if(this.getUploadingFiles().length === 0){
                        isset_images3 = true;
                        hideAlertTime();
                    }
                });
            }
        });

        $("#save-promotion").click(() => {
            showAlertTime();
            storePromotion();
        });

        function storePromotion(){
            $.ajax({
                url: "{{ route('table-store-imgs-4') }}",
                data: $("#form-promotion").serialize(),
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
                    $("#id_promotion_save").val(res.split('-')[1]);
                    myDropzone3.processQueue();
                    setTimeout(() => {
                        if (isset_images3 == false) {
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

        function validateDataUpdate(){
            showAlertTime2();
            updateData();
        }

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

        function hideAlertTime2() {
            setTimeout(() => {
                window.location.reload();
            }, 3000);

            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                title: "Registro editado exitosamente",
                timer: 3000
            });
        }

        function showExistAlert2(){
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

        function updateData() {
            $.ajax({
                url: "{{ route('update-store-imgs-2') }}",
                data: $("#form2").serialize(),
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
                    myDropzone2.processQueue();
                    setTimeout(() => {
                        if (isset_images2 == false) {
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

        function seleccionarProductoPromocion(id){
            $("#product_stores_id").val(id);
            $("#save-promotion").prop('disabled', false); 
        }
</script>
@endsection

