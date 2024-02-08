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
        top: 43px !important;
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
            if($_SERVER['REQUEST_URI'] == '/register-data-grua'){
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
                    Estas a un paso, por favor registra la información de tu grua para poder disfrutar de todos nuestros beneficios
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
                    <h3 class="fw-bolder">Registra los datos de tu grua</h3>
                </div>
                <div class="card-body">
                    <x-validation-errors class="mb-4" />
    
                    <form method="POST" action="{{ route('register-store') }}" id="form">
                        @csrf

                        <input type="hidden" name="label" value="Tiendas">
                        <input type="hidden" name="users_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="link" value="">
                        <input type="hidden" name="status" value="0">
                        <input type="hidden" name="score_store" value="0">
                        
                        <div class="row">
                            <div class="col-md-6 form-group d-none">
                                <label class="py-3" for="name">{{ __('Tipo de tienda') }}</label>
                                <select name="type_stores_id" class="form-select mt-1" value="3">
                                    @foreach ($type_stores as $type_store)
                                        <option value="{{$type_store->id}}">{{$type_store->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Ciudad') }}</label>
                                <input type="hidden" name="cities_id" id="city_store_data_id">
                                <div class="autocomplete">
                                    <input class="form-select" type="text" id="myInput" placeholder="Busca y selecciona una ciudad...">
                                    <ul id="myUL">
                                        @foreach ($cities as $city)
                                            <li><a onclick="seleccionarCiudad({{$city->id}})">{{$city->name}}</a></li>
                                        @endforeach
                                    </ul>
                                  </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Nombre de la grua') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="name" id="name" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Descripcion de la grua') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="description" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Correo') }}</label>
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
                                <label class="py-3" for="name">{{ __('Capacidad') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese una capacidad" class="block mt-1 w-full" type="text" name="capacidad" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Tipo') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un tip" class="block mt-1 w-full" type="text" name="tipo" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Dimensiones') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese las dimensiones" class="block mt-1 w-full" type="text" name="dimensiones" required/>
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
    maxFilesize : 5,
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
        var data = $("#form").serialize().split('&');
        var boolean = true;
        data.forEach((key) => {
        let value = key.split('=')[1];
        let field = key.split('=')[0];
        if(field.includes('link')) return false;
        if(field.includes('product_stores_id')) return false;
        if(value == null || value == ''){
            boolean = false;
        }
    });

    if(!boolean){
        Swal.fire({
            title: "Campos ingresados no válidos",
            icon: "error",
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
        return false;
    } 
            
    showAlertTime();
    storeData();
}

function storeData(){
    $.ajax({
        url: "{{route('table-store-imgs')}}",
        data: $("#form").serialize(),
        method: "POST",
        success(response){
            var res = JSON.parse(response);
            $("#id_table").val(res.split('-')[1]);
            $("#table").val(res.split('-')[0]);
            myDropzone.processQueue();
            setTimeout(() => {
                if(isset_images == false){                        
                    hideAlertTime();
                }
            }, 3000);
        },error(err){
            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'error',
                showConfirmButton: false,
                title: "Ups ha ocurrido un error",
                timer: 3000
            });
            return false;
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