<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Metas -->
    <title>
        Tulobuscas
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
    integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

    @livewireStyles

    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo del cuerpo de la página */
        }

        .navbar {
            background-color: #ffffff; /* Color de fondo del header */
            border-bottom: 1.4rem solid #6495ED; /* Color de la franja justo debajo del header */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo opaco del cuerpo de la página */
            padding: 20px; /* Espaciado interior del cuerpo de la página */
            border-radius: 10px; /* Bordes redondeados del cuerpo de la página */
            margin-top: 20px; /* Margen superior para separar del header */
        }

        .fa-solid{
            margin-left: .5rem;
            margin-right: .5rem;
        }

        a{
            color: #4a4a4a !important;
            font-size: 1.1rem !important;
        }

        a:hover{
            color: #242424 !important;s
        }

        .sub-item{
            margin-left: .5rem;
        }

        .autocomplete {
        position: relative;
        display: block;
        }

        #myInput {
        border: 1px solid #ccc;
        /*padding: 10px;*/
        }

        #myUL {
        list-style-type: none;
        padding: 0;
        margin: 0;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: none;
        width: 100%;
        z-index: 1000;
        max-height: 150px; /* Altura máxima para el scroll */
        overflow-y: auto; /* Habilitar el scroll vertical */
        display: none; /* Ocultar la lista inicialmente */
        }

        #myUL li {
        cursor: pointer;
        }

        #myUL li a {
        padding: 10px;
        display: block;
        text-decoration: none;
        color: #000;
        }

        #myUL li a:hover {
        background-color: #f4f4f4;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    @livewire('nav-bar-admin')
    <div class="row">
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
                
                            @if (session()->has('info'))
                    
                                <div class="alert alert-info">
                    
                                    {{ session('info') }}
                    
                                </div>
                    
                            @endif
                    
                        </div>
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
                                        <div class="col-md-6">
                                            Productos
                                        </div>
                                        <div class="col-md-4 d-flex" style="align-content: center;
                                        align-items: center;">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal1">{{__('Agregar')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nombre</th>
                                                    <th>Marca</th>
                                                    <th>Subcategoria</th>
                                                    <th>Tipo producto</th>
                                                    <th>Fecha de Creacion</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->id}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->name}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                                margin-top: .4rem;">{{$product->brand->description}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->subcategory->name}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->typeProduct->description}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->created_at->format('d-m-Y')}}</p></th>
                                                        <td class="text-start">
                                                            <a onclick="editUser(
                                                                <?php
                                                                    $arrayExtraFields = [];
                                                                    $count = 0;
                                                                    echo "['id|sub_categories_id|cylinder_capacities_id|models_id|boxes_id|type_products_id|brands_id|name|description|code|image|count|link|reference|detail',";
                                                                    
                                                                    echo "'$product->id','$product->sub_categories_id','$product->cylinder_capacities_id','$product->models_id','$product->boxes_id','$product->type_products_id','$product->brands_id','$product->name','$product->description','$product->code','$product->image','$product->count','$product->link','$product->reference','$product->detail'";
                
                                                                    if(isset($product->aditionalPictures)){
                                                                        echo ",'images:";
                                                                        foreach($product->aditionalPictures as $index => $image){
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
                                                            <a onclick="deleteUser({{$product->id}})" style="cursor: pointer" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                                                                <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                            </a>
                                                            <form action="{{route('delete-register')}}" id="form-delete-{{$product->id}}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$product->id}}">
                                                                <input type="hidden" name="label" value="Productos">
                                                            </form>
                                                        </td>
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
        </div>
    </div>
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir producto</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form" style="display: contents;">
                    <input type="hidden" name="image" value="">
                    <input type="hidden" name="count" value="0">
                    <input type="hidden" name="link" value="0">
                    <input type="hidden" name="type_request" value="create">
                    <input type="hidden" name="products_id">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombre del producto</label>
                            <input class="form-control mt-3" type="text" name="name" placeholder="Busca y selecciona un producto...">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Categoria</label>
                            <select class="form-select my-3" id="select1" data-type="category">
                                <option value="" selected>Seleccione una categoria</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sub categoria</label>
                            <select class="form-select my-3"  id="select2" name="sub_categories_id">
                                <option value="" selected>Seleccione una subcategoria</option>
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
                                    <option value="{{ $type_product->id }}">{{ $type_product->description }}</option>
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
                        <div class="col-md-12 form-group">
                            <label class="py-3" for="name">{{ __('Imagenes') }}</label>
                            <div class="dropzone" id="myDropzone24">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" id="store">Crear producto</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Editar producto</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit" style="display: contents;">
                    @csrf
                    <input type="hidden" name="image" id="image" value="">
                    <input type="hidden" name="count" id="count" value="0">
                    <input type="hidden" name="link" id="link" value="0">
                    <input type="hidden" name="type_request" value="create">
                    <input type="hidden" name="products_id">
                    <input type="hidden" name="label" value="Productos">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombre del producto</label>
                            <input class="form-control mt-3" type="text" name="name" id="name" placeholder="Busca y selecciona un producto...">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Categoria</label>
                            <select class="form-select my-3" id="select3" data-type="category">
                                <option value="" selected>Seleccione una categoria</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sub categoria</label>
                            <select class="form-select my-3" id="select4" name="sub_categories_id">
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Cilindraje</label>
                            <select class="form-select my-3" id="cylinder_capacities_id" name="cylinder_capacities_id">
                                @foreach ($cylinder_capacities as $cylinder_capacity)
                                    <option value="{{ $cylinder_capacity->id }}">{{ $cylinder_capacity->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Modelo</label>
                            <select class="form-select my-3" name="models_id" id="models_id">
                                @foreach ($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Caja</label>
                            <select class="form-select my-3" name="boxes_id" id="boxes_id">
                                @foreach ($boxes as $box)
                                    <option value="{{ $box->id }}">{{ $box->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tipo producto</label>
                            <select class="form-select my-3" name="type_products_id" id="type_products_id">
                                @foreach ($type_products as $type_product)
                                    <option value="{{ $type_product->id }}">{{ $type_product->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Marca</label>
                            <select class="form-select my-3" name="brands_id" id="brands_id">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Descripción</label>
                            <input type="form-control" name="description" id="description" class="form-control my-3"
                                placeholder="Ingrese una descripción">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Código</label>
                            <input type="form-control" name="code" id="code" class="form-control my-3" placeholder="Ingrese un código">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Referencia</label>
                            <input type="form-control" name="reference" id="reference" class="form-control my-3"
                                placeholder="Ingrese una referencia">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Detalle</label>
                            <input type="form-control" name="detail" id="detail" class="form-control my-3"
                                placeholder="Ingrese un detalle">
                        </div>
                        <label class="py-3" for="name">{{ __('Imagenes') }}</label>
                        <div class="row" id="row-img-update">
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="dropzone" id="myDropzone25">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" id="update">Editar producto</button>
            </div>
          </div>
        </div>
      </div>

    <input type="hidden" id="id_table">
      


    <!--   Core JS Files   -->
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>       
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
            ordering: false
        });

        $("#menu").click(() => {
            if($("#menu").attr('class').includes('act')){
                $("#menu").removeClass('act');
                $(".item-bd").fadeOut();
            }else{
                $("#menu").addClass('act');
                $(".item-bd").fadeIn();
            }
        });

        $("#menu2").click(() => {
            if($("#menu2").attr('class').includes('act')){
                $("#menu2").removeClass('act');
                $(".item-bd2").fadeOut();
            }else{
                $("#menu2").addClass('act');
                $(".item-bd2").fadeIn();
            }
        });

        $("#menu3").click(() => {
            if($("#menu3").attr('class').includes('act')){
                $("#menu3").removeClass('act');
                $(".item-bd3").fadeOut();
            }else{
                $("#menu3").addClass('act');
                $(".item-bd3").fadeIn();
            }
        });

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

        $('#select3').change(function() {
            var select1Value = $(this).val();
            var typeSubCategory = $(this).data('type');
            $('#select4').empty();
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
                        $('#select4').append($('<option>', { 
                            value: '',
                            text : 'Selecciona una opción'
                        }));
                        $.each(data, function(key, value) {
                            if(typeSubCategory == 'module'){
                                $('#select4').append($('<option>', { 
                                    value: value.id,
                                    text : value.description
                                }));
                            }else{
                                $('#select4').append($('<option>', { 
                                    value: value.id,
                                    text : value.name
                                }));
                            }
                        });
                    }
                });
            }
        });


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
                    formData.append("table", 'products');
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
                //window.location.reload();
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
            console.log(array);
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

            console.log(arrayImagenes);


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

        $("#update").click((e) => {
            validateDataUpdate();
        });

        function validateDataUpdate(){
            showAlertTime();
            updateData();
        }

        let myDropzone2 = new Dropzone("#myDropzone25", { 
            url: "{{route('imgs-update')}}",
            headers: {
                'X-CSRF-TOKEN' : "{{csrf_token()}}",
            },
            dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 5)`,
            dictMaxFilesExceeded: "No puedes subir más archivos",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "No puedes subir archivos de este tipo",
            dictRemoveFile: "Remover archivo",
            acceptedFiles: 'image/*',
            maxFilesize : 5,
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

    </script>
</body>

</html>