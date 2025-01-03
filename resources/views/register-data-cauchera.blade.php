<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    html{
        overflow-x: hidden !important;
    }

    tr{
        border: aliceblue;
    }

    .ps__rail-x{
        display: none !important;
    }

    .dropzone .dz-preview .dz-error-message {
        top: .3rem !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    label{
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .cursor-pointer{
        cursor: pointer;
    }

</style>

@php
    if(isset(Auth::user()->id)){
        if(Auth::user()->email_verified_at != "" && Auth::user()->store){
            if($_SERVER['REQUEST_URI'] == '/register-data-store'){
                echo "<script>window.location.replace('/dashboard');</script>";
            }
        }
    }
@endphp

<x-app-layout>
    <div class="container" style="max-width: 100%">
        <div class="row mt-3">
            <div>
                <div class="alert alert-info" style="text-align: center">
                    Estas a un paso, por favor registra la información de tu taller para poder disfrutar de todos nuestros beneficios
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="card my-3" style="border: solid 1px #aaa !important;
            border-radius: 15px !important;">
                <div class="card-header text-center" style="border: none;
                background: white;
                padding: 1rem;
                border-radius: 15px;">
                    <h3 class="fw-bolder">Registra los datos de tu cauchera</h3>
                </div>
                <div class="card-body">
                    <x-validation-errors class="mb-4" />
    
                    <form id="form">
                        <input type="hidden" name="users_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="link" value="">
                        <input type="hidden" name="status" value="0">
                        <input type="hidden" name="score_store" value="0">
                        <input type="hidden" name="capacidad">
                        <input type="hidden" name="tipo">
                        <input type="hidden" name="dimensiones">
                        <input type="hidden" name="image" value="">
                        <input type="hidden" name="image2" value="">
                        <input type="hidden" name="type_stores_id" value="4">
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Categoria Tienda') }}</label>
                                <select name="categories_stores_id" class="form-select mt-1">
                                    <option value="">Selecciona un categoria</option>
                                    @foreach ($categories_stores as $category)
                                        <option value="{{$category->id}}">{{$category->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Estado') }}</label>
                                <select name="states_id" class="form-select mt-1">
                                    <option value="">Selecciona un estado</option>
                                    @foreach ($states as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Municipio') }}</label>
                                <select class="form-select" id="municipalities_id" name="municipalities_id">
                                    <option value="">Selecciona un municipio</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Sector') }}</label>
                                <select class="form-select" id="sectors_id" name="sectors_id">
                                    <option value="">Selecciona un sector</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Nombre del taller') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="name" id="name" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Descripción del taller') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="description" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Correo del taller') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="email" name="email" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Dirección') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="address" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('RIF') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="RIF" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Telefono') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="phone" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Imagen') }}</label>
                                <div class="dropzone" id="myDropzone24">
                                </div>
                            </div>
                        </div>
        
        
                        <div class="flex items-center justify-end mt-4">
                            <x-button type="button" class="ms-4" id="btn-register-data-store">
                                {{ __('Registrar datos') }}
                            </x-button>
                        </div>
                    </form>
                    <input type="hidden" id="id_table">
                    <input type="hidden" id="table">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

$("#btn-register-data-store").click((e) => {
    validateDataStore();
});

Dropzone.autoDiscover = false;

var isset_images = false;

var myDropzone = new Dropzone("#myDropzone24", { 
    url: "imgs-store-data",
    headers: {
        'X-CSRF-TOKEN' : "{{csrf_token()}}",
    },
    dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 2)`,
    dictMaxFilesExceeded: "No puedes subir más archivos",
    dictCancelUpload: "Cancelar subida",
    dictInvalidFileType: "No puedes subir archivos de este tipo",
    dictRemoveFile: "Remover archivo",
    acceptedFiles: 'image/*',
    maxFilesize: 2.048,
    dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
    maxFiles: 2,
    autoProcessQueue: false,
    addRemoveLinks: true,
    parallelUploads: 5,
    init: function(){
        this.on("sending", function(file, xhr, formData){
            formData.append("id", `${$("#id_table").val()}`);
            formData.append("table", `${$("#table").val()}`);
        });

        this.on("success", function(file, response) {
            if(file.status != 'success'){
                return false;
            }
            if(this.getUploadingFiles().length === 0){
                isset_images = true;
                hideAlertTime();
            }
        });
    }
}); 

function validateDataStore(){            
    showAlertTime();
    storeData();
}

function storeData(){
    $.ajax({
        url: "{{route('table-store-imgs-2')}}",
        data: $("#form").serialize(),
        headers: {
            'X-CSRF-TOKEN' : "{{csrf_token()}}",
        },
        method: "POST",
        success: function(response) {
            var res = JSON.parse(response);
            $("#id_table").val(res.split('-')[1]);
            $("#table").val(res.split('-')[0]);
            myDropzone.processQueue();
            setTimeout(() => {
                if(isset_images == false){                        
                    hideAlertTime();
                }
            }, 3000);
        },
        error: function(xhr) {
            if(xhr.status === 422) {
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

function showAlertTime(){
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

function hideAlertTime(){
    setTimeout(() => {
        window.location.replace(`/tienda/${$("#name").val().replaceAll(' ', '-')}`);
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
</script>

<script>
    function seleccionarMunicipio(id){
        $.ajax({
            url: '/municipalities/' + id + '/sectors',
            type: 'GET',
            success: function(data) {
                var options = '<option value="">Selecciona un sector</option>';
                data.forEach((key) => {
                    options += '<option value="' + key.id + '">' + key.description + '</option>';
                });
                $('#sectors_id').html(options);
            }
        });
    }

    function seleccionarEstado(id){
        $.ajax({
            url: '/states/' + id + '/municipalities',
            type: 'GET',
            success: function(data) {
                var options = '<option value="">Selecciona un municipio</option>';
                data.forEach((key) => {
                    options += '<option value="' + key.id + '">' + key.name + '</option>';
                });
                $('#municipalities_id').html(options);
            }
        });
    }

    $(document).ready(function() {
        $('select[name="states_id"]').change(function() {
            var stateId = $(this).val();
            if(stateId) {
                seleccionarEstado(stateId);
            } else {
                $('#municipalities_id').html('<option value="">Selecciona un municipio</option>');
            }
        });

        $('#municipalities_id').change(function() {
            var municipalityId = $(this).val();
            if(municipalityId) {
                seleccionarMunicipio(municipalityId);
            } else {
                $('#sectors_id').html('<option value="">Selecciona un sector</option>');
            }
        });
    });

</script>