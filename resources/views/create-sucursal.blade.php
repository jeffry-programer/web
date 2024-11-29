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

    .btn-confirm {
        background-color: #2a9de2 !important; /* Verde vivo */
        margin-right: 1rem;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .btn-confirm:hover {
        background-color: #2286c4 !important;
    }

    .btn-cancel {
        background-color: #737373 !important; /* Rojo vivo */
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .btn-cancel:hover {
        background-color: #595959 !important;
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
                    Estas a un paso, por favor registra la información de tu sucursal para poder disfrutar de todos nuestros beneficios
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
                    <h3 class="fw-bolder">Registra los datos de tu sucursal</h3>
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
                        <input type="hidden" name="type_stores_id" value="1">
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Categoria sucursal') }}</label>
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
                                <label class="py-3" for="name">{{ __('Nombre de la sucursal') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="name" id="name" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Descripcion de la sucursal') }}</label>
                                <input class="form-control" placeholder="Describe brevemente los servicios de tu sucursal" class="block mt-1 w-full" type="text" name="description" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Nombre del usuario de la sucursal') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="name2" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Correo de la sucursal') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese el correo electrónico" class="block mt-1 w-full" type="email" name="email" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Contraseña') }}</label>
                                <div class="relative">
                                    <input id="password" class="form-control" type="password" placeholder="Por favor ingresa una contraseña"
                                        name="password" required autocomplete="new-password" />
                                    <button type="button" onclick="togglePasswordVisibility()"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                        style="position: absolute;right: .8rem;top: .6rem;">
                                        <svg id="eye-icon" class="h-5 w-5 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Repita la contraseña') }}</label>
                                <div class="relative">
                                    <input id="password_confirmation" class="form-control" type="password" placeholder="Por favor repite la contraseña"
                                        name="password_confirmation" required autocomplete="new-password" />
                                    <button type="button" onclick="togglePasswordVisibility2()"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                        style="position: absolute;right: .8rem;top: .6rem;">
                                        <svg id="eye-icon" class="h-5 w-5 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Dirección') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese la dirección exacta de tu sucursal" class="block mt-1 w-full" type="text" name="address" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('RIF') }}</label>
                                <input class="form-control" class="block mt-1 w-full" value="{{ $store->RIF }}" type="text" disabled/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Telefono') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese el telefono de contacto " class="block mt-1 w-full" type="text" name="phone" required/>
                            </div>
                            @if(Auth::user()->store->type_stores_id == env('TIPO_GRUA_ID'))
                                <div class="col-md-6 form-group">
                                    <label class="py-3" for="name">{{ __('Capacidad') }}</label>
                                    <input class="form-control" placeholder="Por favor ingrese una capacidad" class="block mt-1 w-full" type="text" name="capacidad" required/>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="py-3" for="name">{{ __('Tipo') }}</label>
                                    <select class="form-select" name="tipo">
                                        <option value="Plataforma plana">Plataforma plana</option>
                                        <option value="Grúas de gancho">Grúas de gancho</option>
                                        <option value="Grúas de arrastre">Grúas de arrastre</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="py-3" for="name">{{ __('Dimensiones') }}</label>
                                    <input class="form-control" placeholder="Por favor ingrese las dimensiones" class="block mt-1 w-full" type="text" name="dimensiones" required/>
                                </div>
                            @endif
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
    Swal.fire({
        title: "Importante",
        text: "El correo que ingreses será tu acceso para entrar a tu nueva sucursal. Asegúrate de escribirlo bien, ya que solo podrás usar este correo para iniciar sesión y recuperar tu cuenta si olvidas la contraseña.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
        customClass: {
            confirmButton: 'btn-confirm',
            cancelButton: 'btn-cancel'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            validateDataStore();
        }
    });
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
    maxFiles: 2,
    maxFilesize: 2.048,
    dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido    maxFiles: 2,
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
        url: "{{route('table-store-imgs-6')}}",
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
        window.location.replace(`/dashboard`);
    }, 4000);

    Swal.fire({
        toast: true,
        position: 'center',
        icon: 'success',
        showConfirmButton: false,
        title: "Registro agregado exitosamente",
        text: "Por favor inicia sesión en tu nueva sucursal",
        timer: 4000
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

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }

    function togglePasswordVisibility2() {
        var passwordInput = document.getElementById('password_confirmation');
        var eyeIcon = document.getElementById('eye-icon2');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }

</script>