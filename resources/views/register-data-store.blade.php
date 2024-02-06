@section('css')
<link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endsection

<x-app-layout>
    <div class="container" style="max-width: 100%">
        <div class="row mt-3">
            <div>
                <div class="alert alert-info" style="text-align: center">
                    Estas a un paso, por favor registra la información de tu tienda para poder disfrutar de todos nuestros beneficios
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
                    <h3 class="fw-bolder">Registra los datos de tu tienda</h3>
                </div>
                <div class="card-body">
                    <x-validation-errors class="mb-4" />
    
                    <form method="POST" action="{{ route('register-store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Tipo de tienda') }}</label>
                                <select name="type_store" class="form-select mt-1">
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
                                <label class="py-3" for="name">{{ __('Nombre de la tienda') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="name" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Descripcion de la tienda') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="description" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Correo de la tienda') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="email" name="email" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('Dirección') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="address" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="py-3" for="name">{{ __('RIF') }}</label>
                                <input class="form-control" placeholder="Por favor ingrese un nombre" class="block mt-1 w-full" type="text" name="rif" required/>
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
                            <x-button class="ms-4">
                                {{ __('Registrar datos') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>