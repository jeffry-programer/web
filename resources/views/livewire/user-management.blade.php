@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

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

    .alert-info{
        color: white !important;
    }

    label{
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .cursor-pointer{
        cursor: pointer;
    }
</style>


<div class="container">

    @php
        if(isset(Auth::user()->id)){
            if(Auth::user()->email_verified_at == "" && $_SERVER['REQUEST_URI'] != '/email/verify'){
            echo "<script>window.location.replace('/email/verify');</script>";
            }
            if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 2 && !Auth::user()->store){
            if($_SERVER['REQUEST_URI'] != '/register-data-store'){
                echo "<script>window.location.replace('/register-data-store');</script>";
            }
            }
            if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 4 && !Auth::user()->store){
            if($_SERVER['REQUEST_URI'] != '/register-data-taller'){
                echo "<script>window.location.replace('/register-data-taller');</script>";
            }
            }
            if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 5 && !Auth::user()->store){
            if($_SERVER['REQUEST_URI'] != '/register-data-grua'){
                echo "<script>window.location.replace('/register-data-grua');</script>";
            }
            }
        }
    @endphp
    
    <div class="row">
        <div class="col-md-2">
            <ul class="navbar-nav">
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}" style="cursor: pointer" id="menu">
                        <span class="nav-link-text ms-1"><i class="fa-solid fa-bars"></i>{{__('DB')}}</span>
                    </a>
                </li>
        
                @foreach ($tables as $table)
                    <?php 
                        $link = str_replace(" ", "_", $table->label);
                    ?>
                    <li class="nav-item pb-2 item-bd sub-item" style="display: none;">
                        <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                            href="/admin/table-management/{{$link}}" id="menu">
                            <span class="nav-link-text ms-1">{{$table->label}}</span>
                        </a>
                    </li>
                @endforeach
        
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        style="cursor: pointer" id="menu2">
                        <span class="nav-link-text ms-1"><i class="fa-solid fa-bars"></i>{{__('Productos')}}</span>
                    </a>
                </li>

                <li class="nav-item pb-2 item-bd2 sub-item" style="display: none;">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        href="/admin/products" id="menu">
                        <span class="nav-link-text ms-1">Productos</span>
                    </a>
                </li>
        
                @foreach ($tables2 as $table)
                    <?php 
                        $link = str_replace(" ", "_", $table->label);
                    ?>
                    @if($table->label != 'Productos')
                        <li class="nav-item pb-2 item-bd2 sub-item" style="display: none;">
                            <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                                href="/admin/table-management/{{$link}}" id="menu">
                                <span class="nav-link-text ms-1">{{$table->label}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
                <li class="nav-item pb-2 item-bd2 sub-item" style="display: none;">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        href="/admin/product_store_masive" id="menu">
                        <span class="nav-link-text ms-1">Masivo producto tienda</span>
                    </a>
                </li>


                <li class="nav-item pb-2">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        style="cursor: pointer" id="menu3">
                        <span class="nav-link-text ms-1"><i class="fa-solid fa-bars"></i>{{__('Eliminación masiva')}}</span>
                    </a>
                </li>

                <li class="nav-item pb-2 item-bd3 sub-item" style="display: none;">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        href="/admin/product_delete_masive">
                        <span class="nav-link-text ms-1">Producto</span>
                    </a>
                </li>

                <li class="nav-item pb-2 item-bd3 sub-item" style="display: none;">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        href="/admin/product_store_delete_masive">
                        <span class="nav-link-text ms-1">Producto Tienda</span>
                    </a>
                </li>
        
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}" href="/admin/table-management/Tiendas">
                        <span class="nav-link-text ms-1"><i class="fa-solid fa-bars"></i>{{__('Tiendas')}}</span>
                    </a>
                </li>
        
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                        href="/admin/table-management/Usuarios">
                        <span class="nav-link-text ms-1"><i class="fa-solid fa-bars"></i>{{__('Usuarios')}}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-10">
            <h2 style="margin-left: 1.5rem;
            margin-bottom: 2rem;
            font-weight: bold;color: #828282">Administración</h2>
            <div class="row">
                <div>
        
                    @if (session()->has('message'))
            
                        <div class="alert alert-primary">
            
                            {{ session('message') }}
            
                        </div>
            
                    @endif
            
                </div>
                <div class="col-12">
                    <div class="card mb-4 mx-4" style="padding: 1.5rem">
                        <div class="card-title" style="font-size: 1.5rem;font-weight: bold;margin-bottom: 1.5rem">
                            <div class="row">                       
                                <div class="col-8">
                                    {{$label}}
                                </div>
                                <div class="col-4 text-end">
                                    <button class="btn btn-primary" id="add-register">{{__('Add new')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="myTable">
                                    <thead>
                                        <tr>
                                            @foreach ($atributes as $field)
                                                @if(!in_array($field, ['updated_at', 'email_verified_at', 'remember_token', 'token', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']))
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        {{ __($field) }}
                                                    </th>
                                                @endif
                                            @endforeach
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                {{ __('actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody style="border-bottom: solid 0.5px #b2b2b2;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    
    <?php 
        if($label == 'Productos'){
            $maxFiles = 5;
        }else if($label == 'Tiendas'){
            $maxFiles = 2;
        }else{
            $maxFiles = 1;
        }
    ?>
    
    <input type="hidden" id="maxFiles" value="<?php echo $maxFiles; ?>">
    
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('Add new')}}</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card" style="width: 72%;left: 16%;">
                    <div class="card-body">
                        <form action="{{route('table-store')}}" method="POST" autocomplete="off" id="form" autocomplete="off">
                        @csrf
                        <?php $count_autocomplete = 0; ?>
                        <?php $data_autocomplete = []; ?>
                        @if($label == 'Productos')
                            <label for="">Categorias</label>
                            <select class="form-select" id="select1" data-type="category" wire:model="category">
                                <option value="" selected>Seleccione una categoria</option>
                                @foreach ($categories as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                            <label for="">Sub Categoria</label>
                            <select class="form-select" id="select2" name="sub_categories_id">
                                <option value="" selected>Seleccione una subcategoria</option>
                            </select>
                        @endif
                        @if($label == 'Perfil operaciones')
                            <label for="">Modulo</label>
                            <select class="form-select" id="select1" data-type="module" wire:model="category">
                                <option value="" selected>Seleccione un modulo</option>
                                @foreach ($modules as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                            <label for="">Operaciones</label>
                            <select class="form-select" id="select2" name="operations_id">
                                <option value="" selected>Seleccione una operación</option>
                            </select>
                        @endif
                        @foreach ($atributes as $field)
                            @if($field != 'created_at' && $field != 'updated_at' && $field != 'id' && $field != 'email_verified_at' && $field != 'remember_token' && $field != 'token' && $field != 'about' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at')
                                @if(str_contains($field, '_id'))
                                    @if($field == 'stores_id' || $field == 'products_id' || $field == 'users_id')
                                        <label for="">{{__($field)}}</label>
                                        <input type="text" required class="form-control" data-name="{{$field}}" placeholder="Escriba y seleccione el campo {{__($field)}}" id="autocomplete-{{$field}}">
                                        <div id="list-{{$field}}"></div>
                                        
                                        <input type="hidden" name="{{$field}}">
                                        <?php 
                                            $autocomplete = true; 
                                            $count_autocomplete++;
    
                                            array_push($data_autocomplete, $field);
                                        ?>
                                    @elseif($field == 'product_stores_id')
                                        <label for="">{{__('products_id')}}</label>
                                        <input type="text" required class="form-control" data-name="products_id" placeholder="Escriba y seleccione el campo {{__('products_id')}}" id="autocomplete-products_id">
                                        <div id="list-products_id"></div>
                                        <input type="hidden" name="products_id">
                                        <input type="hidden" name="stores_id">
                                        <label for="">{{__('stores_id')}}</label>
                                        <input type="text" required class="form-control" data-name="stores_id" placeholder="Escriba y seleccione el campo {{__('stores_id')}}" id="autocomplete-stores_id">
                                        <div id="list-stores_id"></div>
                                        
                                        <input type="hidden" name="{{$field}}">
                                        <?php 
                                            $autocomplete = true; 
                                            $count_autocomplete++;
    
                                            array_push($data_autocomplete, $field);
                                        ?>
                                    @else
                                        @if(!($label == 'Productos' && $field == 'sub_categories_id' || $label == 'Perfil operaciones' && $field == 'operations_id'))
                                            @if($field == 'municipalities_id' && count($states) > 0)
                                                <label for="">Estado</label>
                                                <select class="form-select" name="states_id" id="states_id1">
                                                    <option value="">Seleccione un estado</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            <label for="">{{__($field)}}</label>
                                            <select class="form-select" name="{{$field}}" @if($field == 'municipalities_id') id="municipalities_id1"  @endif @if($field == 'sectors_id') id="sectors_id1"  @endif>
                                                @foreach ($extra_data[$field]['values'] as $value)
                                                    @foreach ($extra_data[$field]['fields'] as $field2)
                                                        @if($field2 == 'email' || $field2 == 'name' || $field2 == 'description')
                                                            <option value="{{$value->id}}">{{$value->$field2}}</option>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        @endif
                                    @endif
                                @elseif((str_contains($field, 'statusplan')))
                                    <label for="">{{__($field)}}</label>
                                    <select name="statusplan" class="form-select">
                                        <option value="Vigente">Vigente</option>
                                        <option value="No vigente">No vigente</option>
                                    </select>
                                @elseif((str_contains($field, 'status')))
                                    <label for="">{{__($field)}}</label>
                                    <select name="{{$field}}" class="form-select">
                                        <option value="0">Inactivo</option>
                                        <option value="1">Activo</option>
                                    </select>
                                @elseif($label == 'Plan contratado' && $field == 'date_end')
                                    <input type="text" name="{{$field}}" class="d-none" value="">
                                @elseif(str_contains($field, 'date'))
                                    <label for="">{{__($field)}}</label>
                                    <input type="date" name="{{$field}}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addDays(365)->format('Y-m-d') }}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                @elseif($field == 'password')
                                    <label>{{__($field)}}</label>
                                    <input type="password" name="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                @elseif($field == 'image' || $field == 'image2')
                                    <?php $image = true; ?>
                                @elseif($field == 'gender')
                                    <label>{{__($field)}}</label>
                                    <select name="{{$field}}" class="form-select">
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                @elseif($field == 'status')
                                    <label>{{__($field)}}</label>
                                    <select name="{{$field}}" class="form-select">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                @elseif($field == 'link')
                                    <input type="text" name="{{$field}}" class="d-none" value="">
                                @elseif($field == 'phone' || $field == 'amount' || $field == 'price')
                                    <label>{{__($field)}}</label>
                                    <input type="number" step="0.01" min="1" name="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                @elseif($field == 'count')
                                    <label class="d-none">{{__($field)}}</label>
                                    <input type="number" step="0.01" name="{{$field}}" required class="form-control d-none" value="0">
                                @elseif((str_contains($field, 'hour')))
                                    <label>{{__($field)}}</label>
                                    <input type="time" name="{{$field}}" class="form-control">
                                @elseif((str_contains($field, 'schedule')))
                                    <label>{{__($field)}}</label>
                                    <textarea name="{{$field}}" class="form-control" placeholder="Ejemplo: De lunes a viernes: 8 am - 5 pm, Sabado: 9 am - 12 pm"></textarea>
                                @elseif((str_contains($field, 'tipo')))
                                    <div class="display-grua">
                                        <label>{{__($field)}}</label>
                                        <select class="form-select" name="tipo">
                                            <option value="Plataforma plana">Plataforma plana</option>
                                            <option value="Grúas de gancho">Grúas de gancho</option>
                                            <option value="Grúas de arrastre">Grúas de arrastre</option>
                                        </select>
                                    </div>
                                @elseif((str_contains($field, 'resource')))
                                    <label>{{__($field)}}</label>
                                    <div class="dropzone" id="myDropzone48"></div>
                                @elseif((str_contains($field, 'default')))
                                    <label>{{__($field)}}</label>
                                    <div class="dropzone" id="myDropzone49"></div>
                                @else
                                    @if($field == 'dimensiones' || $field == 'capacidad')
                                        <div class="display-grua">
                                            <label for="">{{__($field)}}</label>
                                            <input type="text" name="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                        </div>
                                    @else
                                        <label for="">{{__($field)}}</label>
                                        <input type="text" name="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                    @endif
                                @endif
                            @endif
                        @endforeach
                        <input type="hidden" name="label" value="{{$label}}">
                        @isset($image)
                        <label style="margin-top: 1rem;">{{__('images')}}</label>
                        <div class="dropzone" id="myDropzone">
                        </div>
                        @endisset
                    </div>
                </div>
                <div>
    
                    @isset($autocomplete)
            
                        <div class="alert alert-primary mt-3" style="width: 72%;left: 16%;">Para poder guardar el registro debes seleccionar un item por cada autompletado</div>
            
                    @endisset
            
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" style="background:#171717;" data-bs-dismiss="modal">{{__('Close')}}</button>
              <button 
              @if(isset($image) || $label == 'Informaciones')
                type="button" id="store"
              @else
                type="submit"
              @endif
              @isset($autocomplete)
                disabled id="save"
                style="background: #00000029;"
              @endisset
               class="btn btn-primary">{{__('Save changes')}}</button>
            </div>
            </form>
            <input type="hidden" id="id_table">
            <input type="hidden" id="table">
            @isset($autocomplete)
                <input type="hidden" id="data-autocomplete" value="<?php 
                foreach ($data_autocomplete as $index => $key){
                    if($index){
                        echo ",$key";
                    }else{
                        echo "$key";
                    }
                }?>">
                <input type="hidden" id="count-autocomplete" value="<?php echo $count_autocomplete; ?>">
            @endisset
          </div>
        </div>
      </div>
    
      <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('Edit')}}</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('table-update')}}" method="POST"  autocomplete="off" id="form-edit">
                @csrf
                <?php $count_autocomplete = 0; ?>
                <?php $data_autocomplete = []; ?>
            <div class="modal-body">
                <div class="card" style="width: 74%;left: 16%;">
                    <div class="card-body">
                        @foreach ($atributes as $field)
                            @if($field != 'created_at' && $field != 'updated_at' && $field != 'id' && $field != 'email_verified_at' && $field != 'remember_token' && $field != 'token' && $field != 'about' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at')
                                @if(str_contains($field, '_id'))
                                        @if($field == 'stores_id' || $field == 'products_id' || $field == 'users_id')
                                            <label for="">{{__($field)}}</label>
                                            <select class="form-select" name="{{$field}}" id="{{$field}}">
                                                @foreach ($extra_data[$field]['values'] as $value)
                                                    @foreach ($extra_data[$field]['fields'] as $field2)
                                                        @if($field2 == 'email' || $field2 == 'name' || $field2 == 'description')
                                                            <option value="{{$value->id}}">{{$value->$field2}}</option>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        @else
                                            @if($field == 'municipalities_id' && count($states) > 0)
                                                <label for="">Estado</label>
                                                <select class="form-select" name="states_id" id="states_id">
                                                    <option value="">Seleccione un estado</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            <label for="">{{__($field)}}</label>
                                            <select class="form-select" @if($field != 'categories_stores_id') name="{{$field}}" @endif id="{{$field}}">
                                                @foreach ($extra_data[$field]['values'] as $value)
                                                    @foreach ($extra_data[$field]['fields'] as $field2)
                                                        @if($field2 == 'email' || $field2 == 'name' || $field2 == 'description')
                                                            <option value="{{$value->id}}">{{$value->$field2}}</option>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        @endif
                                    @elseif((str_contains($field, 'statusplan')))
                                        <label for="">{{__($field)}}</label>
                                        <select name="statusplan" id="statusplan" class="form-select">
                                            <option value="Vigente">Vigente</option>
                                            <option value="No vigente">No vigente</option>
                                        </select>
                                    @elseif((str_contains($field, 'status')))
                                        @if($label == 'Renovaciones' && $field == 'status')
                                            <label for="">{{__($field)}}</label>
                                            <div class="row">
                                                <div class="col-md-12 d-flex justify-content-start">
                                                    <p class="text-success d-none" id="text-status-renovation-approve"><b>Aprobado</b></p>
                                                    <p class="text-danger d-none" id="text-status-renovation-decline"><b>Rechazado</b></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-danger" id="decline-renovation">Rechazar</button>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-primary" id="downloadReceiptButton">
                                                        Ver comprobante
                                                    </button>
                                                </div>
                                                <!-- Modal para mostrar el comprobante -->
                                                <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="mainModalLabel">Comprobante de pago</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center" style="height: 40rem"> 
                                                                <img id="receiptImage" style="width: 100%;height: 100%;" src="" alt="Comprobante de pago" class="img-fluid">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-success" id="aprove-renovation">Aprobar</button>
                                                </div>
                                                <!-- Modal para mostrar el comprobante -->
                                                <div class="modal fade" id="mainModal2" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="mainModalLabel"></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center" style="height: 27rem"> 
                                                                <h5 style="margin-top: 4rem;font-size:1.5rem;">Comentario Administrador</h5>
                                                                <div style="display: flex;justify-content: center;align-items: center;">
                                                                    <input type="text" id="comment" class="w-50 form-control" style="margin-top: 3rem;" placeholder="Por favor ingrese un comentario">
                                                                </div>

                                                                <div style="margin-top: 2rem;">
                                                                    <button type="button" class="btn btn-success mt-3 w-25" id="aprove-renovation2">Aprobar</button>
                                                                    <button type="button" class="btn btn-danger mt-3 w-25" id="decline-renovation2">Rechazar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($label != 'Renovaciones')
                                            <label for="">{{__($field)}}</label>
                                            <select name="{{$field}}" id="{{$field}}" class="form-select">
                                                <option value="0">Inactivo</option>
                                                <option value="1">Activo</option>
                                            </select>
                                        @endif
                                    @elseif(str_contains($field, 'date'))
                                        <label for="">{{__($field)}}</label>
                                        <input type="date" name="{{$field}}" id="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                    @elseif($field == 'password')
                                        <label>{{__($field)}}</label>
                                        <input type="password" id="{{$field}}" name="{{$field}}" class="form-control" placeholder="Ingrese solo si desea cambiarla">
                                    @elseif($field == 'image' || $field == 'image2' && $label != 'Renovaciones')
                                        <?php $image = true; ?>
                                    @elseif($field == 'link')
                                        <input type="text" name="{{$field}}" id="{{$field}}" class="d-none">
                                    @elseif($field == 'phone')
                                        <label>{{__($field)}}</label>
                                        <input type="number" step="0.01" name="{{$field}}" id="{{$field}}" required class="form-control">
                                    @elseif((str_contains($field, 'hour')))
                                        <label>{{__($field)}}</label>
                                        <input type="time" name="{{$field}}" id="{{$field}}" class="form-control">
                                    @elseif((str_contains($field, 'tipo')))
                                        <div class="display-grua2">
                                            <label>{{__($field)}}</label>
                                            <select class="form-select" name="tipo" id="tipo">
                                                <option value="" selected>Por favor seleccione un tipo de plataforma</option>
                                                <option value="Plataforma plana">Plataforma plana</option>
                                                <option value="Grúas de gancho">Grúas de gancho</option>
                                                <option value="Grúas de arrastre">Grúas de arrastre</option>
                                            </select>
                                        </div>
                                    @else
                                        @if($field == 'dimensiones' || $field == 'capacidad')
                                            <div class="display-grua2">
                                                <label for="">{{__($field)}}</label>
                                                <input type="text" name="{{$field}}" id="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                            </div>
                                        @elseif($label != 'Renovaciones' && $field != 'comment_admin')
                                            <label for="">{{__($field)}}</label>
                                            <input type="text" name="{{$field}}" id="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                        @endif
                                    @endif
                            @endif
                        @endforeach
                        @if(isset($image) && $label != 'Renovaciones')
                            <label style="margin-top: 1rem;">{{__('images')}}</label>
                            <div class="row" id="row-img-update">
                            </div>
                            <div class="dropzone" id="myDropzone2" style="margin-top: 1rem;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="background:#000000;" data-bs-dismiss="modal">{{__('Close')}}</button>
                <button 
                @if(isset($image))
                    type="button" id="update"
                @else
                    type="submit"
                @endif
    
                class="btn btn-primary">{{__('Save changes')}}</button>
            </div>
    
            @isset($autocomplete)
                <input type="hidden" id="data-autocomplete-edit" value="<?php 
                foreach ($data_autocomplete as $index => $key){
                    if($index){
                        echo ",$key";
                    }else{
                        echo "$key";
                    }
                }?>">
                <input type="hidden" id="count-autocomplete-edit" value="<?php echo $count_autocomplete; ?>">
            @endisset
    
            <input type="hidden" name="label" value="{{$label}}" id="label">
            <input type="hidden" name="id" id="id">
            </form>
          </div>
        </div>
      </div>
</div>

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @isset($autocomplete)
        <script>
        var arrayAutoComplete =  $("#data-autocomplete").val().split(',');
        arrayAutoComplete.forEach((key) => {
            $(`#autocomplete-${key}`).on("keyup", function(){
                searchData(`${key}`);
            });
        });

        var arrayAutoCompleteEdit =  $("#data-autocomplete-edit").val().split(',');
        arrayAutoCompleteEdit.forEach((key) => {
            $(`#autocomplete-edit-${key}`).on("keyup", function(){
                searchData(`edit-${key}`);
            });
        });

        function asignId(id, value, field){
            $(`#list-${field}`).html('');
            $(`#autocomplete-${field}`).val(value);
            $(`[name='${$(`#autocomplete-${field}`).data('name')}']`).val(id);
            $("#count-autocomplete").val(($("#count-autocomplete").val() - 1));
            if($("#count-autocomplete").val() == 0){
                if(field.includes('edit')){
                    $("#updat").removeAttr('disabled');
                    $("#updat").removeAttr('style');
                    $("#update").removeAttr('disabled');
                    $("#update").removeAttr('style');
                }else{
                    $("#save").removeAttr('disabled');
                    $("#save").removeAttr('style');
                    $("#store").removeAttr('disabled');
                    $("#store").removeAttr('style');
                }
            }
            return false;
        }

        function searchData(field){
            var searchTable = field.replaceAll('_id','');
            searchTable = searchTable.replaceAll('edit-','');
            var valueInput = $(`#autocomplete-${field}`).val();
            $.ajax({
                url: "{{route('search-data')}}",
                data: {_token : "{{csrf_token()}}", table : searchTable, value : valueInput},
                method: "POST",
                success(data){
                    var response = JSON.parse(data);
                    var plantilla = "";
                    if(response.length > 0 && valueInput != ''){
                        plantilla += '<div class="row">';
                        response.forEach((key) => {
                            console.log(key);
                            if(key.name == null) key.name = key.email;
                            plantilla += `<div class="d-flex col-12 ms-4 my-2" style="cursor: pointer;font-size: 0.9rem;color: #5e5e5e;" onclick="asignId(${key.id},'${key.name}','${field}')">${key.name}</div>`;
                        });
                        plantilla +='</div>';
                    }
                    $(`#list-${field}`).html(plantilla);
                    $(`#list-${field}`).show();
                },
                error(err){
                    console.log(err);
                }
            })
        }
        </script>
    @endisset
   
    <script>
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10, // Configuración para paginar de 10 en 10
            ajax: {
                url: '{{ route('your.data.route') }}',
                data: function (d) {
                    d.label = '{{ $label }}';
                }
            },
            columns: [
                @foreach ($atributes as $field)
                    @if(!in_array($field, ['updated_at', 'email_verified_at', 'remember_token', 'token', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']))
                        { data: '{{ $field }}' },
                    @endif
                @endforeach
                { data: 'actions', orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: -1, // Índice de la columna 'actions' (última columna)
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).css('display', 'flex'); // Aplicar display: flex
                        $(td).css('border', 'none'); // Quitar borde
                    }
                }
            ],"oLanguage": {
                "sSearch": "{{__('Search')}}",
                "sEmptyTable": "No hay información para mostrar"
            },"language": {
                "zeroRecords": "{{__('No matching records found')}}",
                "infoEmpty": "{{__('No records available')}}",
                "paginate": {
                    "previous": "{{__('Previous')}}",
                    "next": "{{__('Next')}}"
                },
                "lengthMenu": "{{__('Showing')}} _MENU_ {{__('entries')}}",
                "infoFiltered":   "({{__('filtered from')}} _TOTAL_ {{__('total entries')}})",
                "info": "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
            },
        });
    
        $("#add-register").click(() => {
            $("#sidenav-collapse-main").addClass('o-hidden');
            $("#exampleModal").modal('show');
        });
    
        $("#exampleModal").on("hidden.bs.modal", function () {
            $("#sidenav-collapse-main").removeClass('o-hidden');
        });
    
        $("#exampleModal2").on("hidden.bs.modal", function () {
            $("#sidenav-collapse-main").removeClass('o-hidden');
        });
    
        function deleteUser(id){
            $("#sidenav-collapse-main").addClass('o-hidden');
            Swal.fire({
                title: "¿Seguro que quieres eliminar este registro?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: `Cancelar`
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $("#form-delete-"+id).submit();
                }else{
                    $("#sidenav-collapse-main").removeClass('o-hidden');
                }
            });
        }
    
        function editUser(array){
            fields = array[0].split("|");
            array.shift();
            var arrayImagenes = [];
            fields.forEach((key, index) => {
                if(key.includes('password')) return false;
                if(key.includes('image')){
                    console.log($("#label").val());
                    if($("#label").val() == 'Renovaciones'){
                        $("#receiptImage").attr('src', array[index]);
                    }
                    arrayImagenes.push(array[index]);
                }else if(key.includes('date')){
                    $(`#${key}`).val(array[index].split(' ')[0]);
                }else{
                    if(key == 'status_renovation' && (array[index] == 'approve' || array[index] == 'decline')){
                        $("#aprove-renovation").hide();
                        $("#decline-renovation").hide();

                        if(array[index] == 'approve'){
                            $("#text-status-renovation-approve").removeClass('d-none');
                            $("#text-status-renovation-decline").addClass('d-none');
                        }else{
                            $("#text-status-renovation-decline").removeClass('d-none');
                            $("#text-status-renovation-approve").addClass('d-none');
                        }
                    }
                    $(`#${key}`).val(array[index]);
                }
            });


            if(arrayImagenes.length > 0){
                var arrayImagenes = arrayImagenes.concat(array.at(-1).replaceAll('images:','').split('|'));
                var plantilla = '';
                arrayImagenes.forEach((key) => {
                    if(key != '' && key.includes('images')){
                        key = key.replaceAll('/storage','storage');
                        nameImg = key;
                        plantilla += `<div class="col-12 col-md-4" style="position: relative;margin-top: 1rem;"><img src="{{asset('${nameImg}')}}" style="width: 9.5rem;height: 6.5rem;" alt=""><a style="cursor:pointer" onclick="deleteImg('${nameImg}');"><img src="{{asset('/storage/x.png')}}" alt="" style="position: absolute;width: 1.4rem;left: 8.8rem;background: white;border-radius: 100%;top: 0.1rem;"></a></div>`;
                    }
                });
                $("#row-img-update").html(plantilla);
                $("#row-img-update").show();
            }
            
            $("#sidenav-collapse-main").addClass('o-hidden');
            $("#exampleModal2").modal("show");
        }
    </script>

    @isset($image)
    <script>
        Dropzone.autoDiscover = false;

        var isset_images = false;

        let myDropzone = new Dropzone("#myDropzone", { 
            url: "{{route('imgs-store')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: ${$("#maxFiles").val()})`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize: 2.048,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
            maxFiles: $("#maxFiles").val(),
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

        let myDropzone2 = new Dropzone("#myDropzone2", { 
            url: "{{route('imgs-update')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: ${$("#maxFiles").val()})`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize: 2.048,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 2.048 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
            maxFiles: $("#maxFiles").val(),
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function(){
                this.on("sending", function(file, xhr, formData){
                    formData.append("id", `${$("#id").val()}`);
                    formData.append("table", `${$("#table").val()}`);
                });

                this.on("addedfile",  function(file) {
                    console.log("A file has been added");
                });


                this.on("success", function(file, response) {
                    if(file.status != 'success'){
                        return false;
                    }
                    if(this.getUploadingFiles().length === 0){
                        isset_images = true;
                        hideAlertTime2();
                    }
                });
            }
        });
    </script>
    @endisset

    <script>
        $('#select1').change(function() {
            var select1Value = $(this).val();
            var typeSubCategory = $(this).data('type');
            $('#select2').empty();
            if(select1Value !== '') {
                $.ajax({
                    url: "{{ route('obtener-sucategorias') }}",
                    data: {'id' : select1Value, 'type' : typeSubCategory},
                    headers: {
                        'X-CSRF-TOKEN' : "{{csrf_token()}}",
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        $('#select2').append($('<option>', { 
                            value: '',
                            text : 'Selecciona una opción'
                        }));
                        $.each(data, function(key, value) {
                            if(typeSubCategory == 'module'){
                                $('#select2').append($('<option>', { 
                                    value: value.id,
                                    text : value.description
                                }));
                            }else{
                                $('#select2').append($('<option>', { 
                                    value: value.id,
                                    text : value.name
                                }));
                            }
                        });
                    }
                });
            }
        });

        /*------------------------Create----------------------------*/
        $('#states_id1').change(function() {
            $.ajax({
                  url: '/states/' + $("#states_id1").val() + '/municipalities',
                  type: 'GET',
                  success: function(data) {
                      var options = '<option value="">Selecciona un municipio</option>';
                      data.forEach((key) => {
                          options += `<option value="${key.id}">${key.name}</option>`;
                      });
                      $('#municipalities_id1').html(options);
                  }
              });
        });

        $('#municipalities_id1').change(function() {
            $.ajax({
                  url: '/municipalities/' + $("#municipalities_id1").val() + '/sectors',
                  type: 'GET',
                  success: function(data) {
                      var options = '<option value="">Selecciona un sector</option>';
                      data.forEach((key) => {
                          options += `<option value="${key.id}">${key.description}</option>`;
                      });
                      $('#sectors_id1').html(options);
                  }
              });
        });

        /*------------------------Edit----------------------------*/
        $('#states_id').change(function() {
            $.ajax({
                  url: '/states/' + $("#states_id").val() + '/municipalities',
                  type: 'GET',
                  success: function(data) {
                      var options = '<option value="">Selecciona un municipio</option>';
                      data.forEach((key) => {
                          options += `<option value="${key.id}">${key.name}</option>`;
                      });
                      $('#municipalities_id').html(options);
                  }
              });
        });

        $('#municipalities_id').change(function() {
            $.ajax({
                  url: '/municipalities/' + $("#municipalities_id").val() + '/sectors',
                  type: 'GET',
                  success: function(data) {
                      var options = '<option value="">Selecciona un sector</option>';
                      data.forEach((key) => {
                          options += `<option value="${key.id}">${key.description}</option>`;
                      });
                      $('#sectors_id').html(options);
                  }
              });
        });

        fillCategoriesSelect($('[name="type_stores_id"]').val(), $('[name="categories_stores_id"]'));

        $('[name="type_stores_id"]').change(() => {
            const typeStoresId = $('[name="type_stores_id"]').val();

            if (typeStoresId == {{ env('TIPO_GRUA_ID') }}) {
                $(".display-grua").css('display', 'block');
            } else {
                $(".display-grua").css('display', 'none');
            }

            // Llenar el select de categorías
            fillCategoriesSelect(typeStoresId, $('[name="categories_stores_id"]'));
        });

        $('#type_stores_id').change(() => {
            const typeStoresId = $('#type_stores_id').val();

            if (typeStoresId == {{ env('TIPO_GRUA_ID') }}) {
                $(".display-grua2").css('display', 'block');
            } else {
                $(".display-grua2").css('display', 'none');
            }

            // Llenar el select de categorías
            fillCategoriesSelect(typeStoresId, $('#categories_stores_id'));
        });

        // Función para llenar el select de categorías
        function fillCategoriesSelect(typeStoresId, selectElement) {
            $.ajax({
                url: '/get-categories',
                type: 'GET',
                data: {
                    type_stores_id: typeStoresId
                },
                success: function(response) {
                    selectElement.empty(); // Limpiar el select
                    selectElement.append('<option value="">Seleccione una categoría</option>');

                    // Agregar las opciones al select
                    response.categories.forEach(category => {
                        selectElement.append(`<option value="${category.id}">${category.description}</option>`);
                    });
                },
                error: function(error) {
                    console.error('Error al obtener las categorías:', error);
                }
            });
        }


        function validateVisibilityTypeStore(){
            if ($('[name="type_stores_id"]').val() == {{ env('TIPO_GRUA_ID') }}) {
                $(".display-grua").css('display', 'block'); // Cambiar 'visiblity' a 'visibility'
            } else {
                $(".display-grua").css('display', 'none'); // Cambiar 'visiblity' a 'visibility'
            }
        }

        function validateVisibilityTypeStore2(){
            if ($('#type_stores_id').val() == {{ env('TIPO_GRUA_ID') }}) {
                $(".display-grua2").css('display', 'block'); // Cambiar 'visiblity' a 'visibility'
            } else {
                $(".display-grua2").css('display', 'none'); // Cambiar 'visiblity' a 'visibility'
            }
        }

        $(document).ready(() => {
            validateVisibilityTypeStore();
            validateVisibilityTypeStore2();
        });

        $('#downloadReceiptButton').click(() => {
            const mainModal = new bootstrap.Modal(document.getElementById('mainModal'));
            mainModal.show();
        });

        $('#decline-renovation').click(() => {
            const mainModal = new bootstrap.Modal(document.getElementById('mainModal2'));
            $("#aprove-renovation2").hide();
            $("#decline-renovation2").show();
            mainModal.show();
        });

        $('#aprove-renovation').click(() => {
            const mainModal = new bootstrap.Modal(document.getElementById('mainModal2'));
            $("#aprove-renovation2").show();
            $("#decline-renovation2").hide();
            mainModal.show();
        });

        $("#store").click((e) => {
            validateDataStore();
        });

        $("#update").click((e) => {
            validateDataUpdate();
        });

        function validateDataStore(){
            var data = $("#form").serialize().split('&');
            var boolean = true;
            var incorrectRif = false;
            data.forEach((key) => {
                let value = key.split('=')[1];
                let field = key.split('=')[0];
                if(field.includes('link')) return false;
                if(field.includes('product_stores_id')) return false;
                if(field.includes('capacidad')) return false;
                if(field.includes('dimensiones')) return false;
                if(field.includes('tipo')) return false;
                if(field.includes('RIF') && value.length < 7){
                    incorrectRif = true;
                }
                if(value == null || value == ''){
                    boolean = false;
                }
            });

            if(incorrectRif){
                Swal.fire({
                    title: "Error: El RIF debe tener minimo 8 carácteres",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
                return false;
            }

            if(!boolean){
                Swal.fire({
                    title: "Error: Valores inválidos o campos incompletos.",
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
        
        $("#aprove-renovation2").click(() => {
            aproveRenovation();
        });

        $("#decline-renovation2").click(() => {
            declineRenovation();
        });

        function aproveRenovation(){
            if($("#comment").val() == ''){
                Swal.fire({
                    title: "Debes ingresar un comentario",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });

                return false
            }   

            showAlertTime();

            $.ajax({
                url: "{{route('aprove-renovation')}}",
                data: {
                    'id': $("#id").val(),
                    'comment': $("#comment").val()
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                method: "POST",
                success(response){
                    var res = JSON.parse(response);
                    hideAlertTime3('La renovacion ha sido aprobada exitosamente');
                },error: function(xhr) {
                    if(xhr.status === 422) {
                        console.log(xhr);
                        var error = xhr.responseJSON.error;
                        Swal.fire({
                            title: error,
                            icon: "error",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
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

        function declineRenovation(){
            if($("#comment").val() == ''){
                Swal.fire({
                    title: "Debes ingresar un comentario",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });

                return false
            }   

            showAlertTime();

            $.ajax({
                url: "{{route('decline-renovation')}}",
                data: {
                    'id': $("#id").val(),
                    'comment': $("#comment").val()
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                method: "POST",
                success(response){
                    var res = JSON.parse(response);
                    hideAlertTime3('La renovacion ha sido rechazada exitosamente');
                },error: function(xhr) {
                    if(xhr.status === 422) {
                        console.log(xhr);
                        var error = xhr.responseJSON.error;
                        Swal.fire({
                            title: error,
                            icon: "error",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
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
            var data = $("#form-edit").serialize().split('&');
            var incorrectRif = false;
            var boolean = true;
            data.forEach((key) => {
                let value = key.split('=')[1];
                let field = key.split('=')[0];

                if(field.includes('RIF') && value.length < 7){
                    incorrectRif = true;
                }

                if(field != 'password' && field != 'capacidad' && field != 'tipo' && field != 'dimensiones'){
                    if(value == null || value == ''){
                        boolean = false;
                    }
                }
            });

            if(incorrectRif){
                Swal.fire({
                    title: "Error: El RIF debe tener minimo 8 carácteres",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
                return false;
            }

            if(!boolean){
                Swal.fire({
                    title: "Todos los campos son requeridos",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
                return false; Swal.fire({
                    title: "Todos los campos son requeridos",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
                return false;
            } 
            
            showAlertTime();
            updateData();
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

        function hideAlertTime2(){
            setTimeout(() => {
                window.location.reload();
            }, 3000);

            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                title: "Registro editado exitosamente!!",
                timer: 3000
            });
        }

        function hideAlertTime3(text){
            setTimeout(() => {
                window.location.reload();
            }, 3000);

            Swal.fire({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                title: text,
                timer: 3000
            });
        }

        function storeData() {
            $.ajax({
                url: "{{route('table-store-imgs')}}",
                data: $("#form").serialize(),
                method: "POST",
                success(response) {
                    var res = JSON.parse(response);
                    $("#id_table").val(res.split('-')[1]);
                    $("#table").val(res.split('-')[0]);

                    let table = $("#table").val();
                    let uploadPromises = [];

                    if (table === 'informations') {
                        // Procesar las colas de ambos Dropzones
                        uploadPromises.push(processDropzone(myDropzone48));
                        uploadPromises.push(processDropzone(myDropzone49));
                    } else {
                        // Procesar solo una cola de Dropzone
                        uploadPromises.push(processDropzone(myDropzone));
                    }

                    // Esperar a que todas las subidas terminen
                    Promise.all(uploadPromises).then(() => {
                        Swal.fire({
                            title: "Datos guardados y archivos subidos con éxito",
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }).catch(err => {
                        console.error("Error en las subidas:", err);
                        Swal.fire({
                            title: "Hubo un problema al subir los archivos",
                            icon: "error",
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                    });
                },
                error(xhr) {
                    handleAjaxError(xhr);
                }
            });
        }

        // Función para procesar un Dropzone y devolver una Promesa
        function processDropzone(dropzoneInstance) {
            return new Promise((resolve, reject) => {
                dropzoneInstance.on("queuecomplete", () => {
                    resolve(); // Resuelve cuando la cola esté vacía
                });

                dropzoneInstance.on("error", (file, errorMessage) => {
                    console.error("Error al subir archivo:", errorMessage);
                    reject(errorMessage); // Rechaza si hay un error
                });

                dropzoneInstance.processQueue(); // Inicia la subida
            });
        }

        // Manejo de errores en la solicitud AJAX
        function handleAjaxError(xhr) {
            if (xhr.status === 422) {
                console.log(xhr);
                var error = xhr.responseJSON.error;
                Swal.fire({
                    title: error,
                    icon: "error",
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
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

        function updateData(){
            $.ajax({
                url: "{{route('update-store-imgs')}}",
                data: $("#form-edit").serialize(),
                method: "POST",
                success(response){
                    var res = JSON.parse(response);
                    $("#id_table").val(res.split('-')[1]);
                    $("#table").val(res.split('-')[0]);
                    myDropzone2.processQueue();
                    setTimeout(() => {
                        if(isset_images == false){
                            hideAlertTime2();
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

        function deleteImg(nameImg){
            Swal.fire({
                title: "¿Seguro que quieres eliminar esta imagen?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: `Cancelar`
            }).then((result) => {
                if (result.isConfirmed){
                    deleteImgDb(nameImg);
                }
            });
        }

        function deleteImgDb(nameImg){
            $.ajax({
                url: "{{route('delete-img')}}",
                data: {_token : "{{csrf_token()}}", nameImg: nameImg.replaceAll('storage','/storage'), id: $("#id").val(), label: $("#label").val()},
                method: 'POST',
                success(response){
                    var plantilla = $("#row-img-update").html();
                    var plantillaRemplazar = `<div class="col-12 col-md-4" style="position: relative;margin-top: 1rem;"><img src="{{asset('${nameImg}')}}" style="width: 9.5rem;height: 6.5rem;" alt=""><a style="cursor:pointer" onclick="deleteImg('${nameImg}');"><img src="{{asset('/storage/x.png')}}" alt="" style="position: absolute;width: 1.4rem;left: 8.8rem;background: white;border-radius: 100%;top: 0.1rem;"></a></div>`;
                    plantilla = plantilla.replaceAll(plantillaRemplazar, '');
                    $("#row-img-update").html(plantilla);
                    $("#row-img-update").show();
                    setTimeout(() => {
                        //window.location.reload();
                    }, 3000);

                    Swal.fire({
                        toast: true,
                        position: 'center',
                        icon: 'success',
                        showConfirmButton: false,
                        title: "Imágen eliminada exitosamente",
                        timer: 3000
                    });
                }
            });
        }
    </script>
    @if($label == 'Informaciones')
    <script>
        let myDropzone48 = new Dropzone("#myDropzone48", { 
            url: "{{route('imgs-store')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes o videos: ${$("#maxFiles").val()})`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*,video/*', // Acepta tanto imágenes como videos
            maxFilesize: 30, // Cambiado de 12 MB a 30 MB
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 12 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
            maxFiles: $("#maxFiles").val(),
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function(){
                this.on("sending", function(file, xhr, formData){
                    formData.append("id", `${$("#id_table").val()}`);
                    formData.append("table", `${$("#table").val()}`);
                    formData.append("type", '1');
                });

                this.on("addedfile",  function(file) {
                    console.log("A file has been added");
                });


                this.on("success", function(file, response) {
                    if(file.status != 'success'){
                        return false;
                    }
                    if(this.getUploadingFiles().length === 0){
                        isset_images = true;
                        //hideAlertTime2();
                    }
                });
            }
        });

        let myDropzone49 = new Dropzone("#myDropzone49", { 
            url: "{{route('imgs-store')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: ${$("#maxFiles").val()})`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*,video/*', // Acepta tanto imágenes como videos
            maxFilesize: 4,
            dictFileTooBig: "El archivo es muy grande. Tamaño máximo permitido: 4 MB.", // Mensaje personalizado cuando el archivo excede el tamaño máximo permitido
            maxFiles: $("#maxFiles").val(),
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 5,
            init: function(){
                this.on("sending", function(file, xhr, formData){
                    formData.append("id", `${$("#id_table").val()}`);
                    formData.append("table", `${$("#table").val()}`);
                    formData.append("type", '2');
                });

                this.on("addedfile",  function(file) {
                    console.log("A file has been added");
                });


                this.on("success", function(file, response) {
                    if(file.status != 'success'){
                        return false;
                    }
                    if(this.getUploadingFiles().length === 0){
                        isset_images = true;
                        //hideAlertTime();
                    }
                });
            }
        });
    </script>
    @endif
@endsection