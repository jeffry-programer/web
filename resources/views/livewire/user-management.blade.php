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
                                                @if($field != 'updated_at' && $field != 'email_verified_at' && $field != 'remember_token' && $field != 'token' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at')
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        {{__($field)}}
                                                    </th>
                                                @endif
                                            @endforeach
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                {{__('actions')}}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key)
                                            <tr>
                                                @foreach ($atributes as $field)
                                                    @if($field != 'updated_at' && $field != 'email_verified_at' && $field != 'remember_token' && $field != 'token' && $field != 'two_factor_secret' && $field != 'two_factor_recovery_codes' && $field != 'two_factor_confirmed_at')
                                                        @if(str_contains($field, '_id'))
                                                            @foreach ($extra_data[$field]['values'] as $value)
                                                                @foreach ($extra_data[$field]['fields'] as $field2)
                                                                    @if($field2 == 'email' || $field2 == 'name' || $field2 == 'description')
                                                                        @if($value->id == $key->$field && !str_contains($field2, '_id'))
                                                                            <td class="ps-4">
                                                                                <p class="text-xs font-weight-bold mb-0">{{$value->$field2}}</p>
                                                                            </td> 
                                                                            @break
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        @elseif((str_contains($field, 'statusplan')))
                                                            <td class="ps-4">
                                                                <p class="text-xs font-weight-bold mb-0">{{$key->$field}}</p>
                                                            </td>             
                                                        @elseif((str_contains($field, 'status')))
                                                            @if($key->$field == 0)
                                                                <td class="ps-4">
                                                                    <p class="text-xs font-weight-bold mb-0">Desactivado</p>
                                                                </td>         
                                                            @else
                                                                <td class="ps-4">
                                                                    <p class="text-xs font-weight-bold mb-0">Activado</p>
                                                                </td>
                                                            @endif
                                                        @elseif((str_contains($field, 'hour')))
                                                            <?php 
                                                                $key->$field = date('H:i', strtotime($key->$field));
                                                            ?>
                                                            <td class="ps-4">
                                                                <p class="text-xs font-weight-bold mb-0">{{$key->$field}}</p>
                                                            </td> 
                                                        @else
                                                            <td class="ps-4">
                                                                <p class="text-xs font-weight-bold mb-0">{{$key->$field}}</p>
                                                            </td> 
                                                        @endif
                                                    @endif
                                                @endforeach
                                                <td class="text-start">
                                                    <a onclick="editUser(
                                                        <?php
                                                            $arrayExtraFields = [];
                                                            $count = 0;
                                                            echo "['";
                                                            foreach($atributes as $field){
                                                                if($field != 'created_at' && $field != 'updated_at'){
                                                                    if($count == 0){
                                                                        echo "$field";
                                                                    }else{
                                                                        echo "|$field";
                                                                    }
                                                                    $count++;
                                                                }
        
                                                                if(str_contains($field, '_id')){
                                                                    array_push($arrayExtraFields, $field);
                                                                }
                                                            }
                                                            echo "'";
                                                            foreach($atributes as $field){
                                                                if($field != 'created_at' && $field != 'updated_at'){
                                                                    echo ",'".$key->$field."'";
                                                                }
                                                            }
        
                                                            if(isset($key->aditionalPictures)){
                                                                echo ",'images:";
                                                                foreach($key->aditionalPictures as $index => $image){
                                                                    if($index == 0){
                                                                        echo "$image->image";
                                                                    }else{
                                                                        echo "|$image->image";
                                                                    }
                                                                }
                                                                echo "'";
                                                            }
                                                            echo "]";
                                                            ?>)" class="mx-3" data-bs-toggle="tooltip"
                                                        data-bs-original-title="Edit user" style="cursor: pointer">
                                                        <i class="fas fa-user-edit text-secondary"></i>
                                                    </a>
                                                        <a onclick="deleteUser({{$key->id}})" class="mx-3" data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit user">
                                                            <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                        </a>
                                                </td>
                                                <form action="{{route('delete-register')}}" id="form-delete-{{$key->id}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$key->id}}">
                                                    <input type="hidden" name="label" value="{{$label}}">
                                                </form>
                                            </tr>
                                        @endforeach
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
                                            <label for="">{{__($field)}}</label>
                                            <select class="form-select" name="{{$field}}">
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
                                    <label>{{__($field)}}</label>
                                    <select class="form-select" name="tipo">
                                        <option value="Plataforma plana">Plataforma plana</option>
                                        <option value="Grúas de gancho">Grúas de gancho</option>
                                        <option value="Grúas de arrastre">Grúas de arrastre</option>
                                    </select>
                                @else
                                    <label for="">{{__($field)}}</label>
                                    <input type="text" name="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
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
              @if(isset($image))
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
                                        @endif
                                    @elseif((str_contains($field, 'statusplan')))
                                        <label for="">{{__($field)}}</label>
                                        <select name="statusplan" id="statusplan" class="form-select">
                                            <option value="Vigente">Vigente</option>
                                            <option value="No vigente">No vigente</option>
                                        </select>
                                    @elseif((str_contains($field, 'status')))
                                        <label for="">{{__($field)}}</label>
                                        <select name="{{$field}}" id="{{$field}}" class="form-select">
                                            <option value="0">Inactivo</option>
                                            <option value="1">Activo</option>
                                        </select>
                                    @elseif($label == 'Plan contratado' && $field == 'date_end')
                                        <input type="hidden" name="{{$field}}" id="{{$field}}">
                                    @elseif(str_contains($field, 'date'))
                                        <label for="">{{__($field)}}</label>
                                        <input type="date" name="{{$field}}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addDays(365)->format('Y-m-d') }}" id="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                    @elseif($field == 'password')
                                        <label>{{__($field)}}</label>
                                        <input type="password" id="{{$field}}" name="{{$field}}" class="form-control" placeholder="Ingrese solo si desea cambiarla">
                                    @elseif($field == 'image' || $field == 'image2')
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
                                        <label>{{__($field)}}</label>
                                        <select class="form-select" name="tipo" id="tipo">
                                            <option value="" selected>Por favor seleccione un tipo de plataforma</option>
                                            <option value="Plataforma plana">Plataforma plana</option>
                                            <option value="Grúas de gancho">Grúas de gancho</option>
                                            <option value="Grúas de arrastre">Grúas de arrastre</option>
                                        </select>
                                    @else
                                        <label for="">{{__($field)}}</label>
                                        <input type="text" name="{{$field}}" id="{{$field}}" required class="form-control" placeholder="{{__('enter a')}} {{__($field)}}">
                                    @endif
                            @endif
                        @endforeach
                        @isset($image)
                        <label style="margin-top: 1rem;">{{__('images')}}</label>
                        <div class="row" id="row-img-update">
                        </div>
                        <div class="dropzone" id="myDropzone2" style="margin-top: 1rem;">
                        </div>
                        @endisset
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
            "oLanguage": {
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
            //console.log(array);
            fields = array[0].split("|");
            array.shift();
            var arrayImagenes = [];
            fields.forEach((key, index) => {
                if(key.includes('password')) return false;
                if(key.includes('image')){
                    arrayImagenes.push(array[index]);
                }else if(key.includes('date')){
                    $(`#${key}`).val(array[index].split(' ')[0]);
                }else{
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
                        plantilla += `<div class="col-12 col-md-4" style="position: relative;margin-top: 1rem;"><img src="{{asset('${nameImg}')}}" style="width: 9.5rem;height: 6.5rem;" alt=""><a style="cursor:pointer" onclick="deleteImg('${nameImg}');"><img src="{{asset('/storage/x.png')}}" alt="" style="position: absolute;width: 1rem;left: 9.25rem;"></a></div>`;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

        $("#store").click((e) => {
            validateDataStore();
        });

        $("#update").click((e) => {
            validateDataUpdate();
        });

        function validateDataStore(){
            var data = $("#form").serialize().split('&');
            var boolean = true;
            data.forEach((key) => {
                let value = key.split('=')[1];
                let field = key.split('=')[0];
                if(field.includes('link')) return false;
                if(field.includes('product_stores_id')) return false;
                if(field.includes('capacidad')) return false;
                if(field.includes('dimensiones')) return false;
                if(field.includes('tipo')) return false;
                if(value == null || value == ''){
                    boolean = false;
                }
            });

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

        function validateDataUpdate(){
            var data = $("#form-edit").serialize().split('&');
            //console.log(data);
            var boolean = true;
            data.forEach((key) => {
                let value = key.split('=')[1];
                let field = key.split('=')[0];
                if(field != 'password' && field != 'capacidad' && field != 'tipo' && field != 'dimensiones'){
                    if(value == null || value == ''){
                        boolean = false;
                    }
                }
            });

            if(!boolean){
                Swal.fire({
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
                    var plantillaRemplazar = `<div class="col-12 col-md-4" style="position: relative;margin-top: 1rem;"><img src="{{asset('${nameImg}')}}" style="width: 9.5rem;height: 6.5rem;" alt=""><a style="cursor:pointer" onclick="deleteImg('${nameImg}');"><img src="{{asset('/storage/x.png')}}" alt="" style="position: absolute;width: 1rem;left: 9.25rem;"></a></div>`;
                    plantilla = plantilla.replaceAll(plantillaRemplazar, '');
                    $("#row-img-update").html(plantilla);
                    $("#row-img-update").show();
                    setTimeout(() => {
                        window.location.reload();
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
    </script>
@endsection