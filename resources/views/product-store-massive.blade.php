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
                                        <form style="display: flex" action="{{route('asociate-products')}}" method="POST" id="form">    
                                        @csrf                 
                                        <div class="col-md-6">
                                            Productos tienda masivo
                                        </div>
                                        <div class="col-md-4 d-flex" style="align-content: center;
                                        align-items: center;">
                                            <label for="" style="font-size: 1rem;" class="me-2">Tienda</label>
                                            <div class="autocomplete" style="width: 17rem;">
                                                <input class="form-select" type="text" id="myInput" placeholder="Busca y selecciona una ciudad...">
                                                <ul id="myUL">
                                                    @foreach ($stores as $store)
                                                        <li><a onclick="seleccionarCiudad({{$store->id}})">{{$store->name}}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <input type="hidden" name="products_id[]" id="selectedIds">
                                        <input type="hidden" name="amounts[]" id="amounts">
                                        <input type="hidden" name="prices[]" id="prices">
                                        <input type="hidden" name="store_id" id="store-id">
                                        <div class="col-md-2 text-center">
                                            <button class="btn btn-primary w-100" type="button" id="associate">{{__('Asociar')}}</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Producto</th>
                                                    <th>Marca</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <th><input style="margin-top: .75rem;" type="checkbox" class="myCheckbox" data-id="{{$product->id}}"></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->name}}</p></th>
                                                        <th><p class="text-xs font-weight-bold mb-0" style="font-weight: initial;
                                                            margin-top: .4rem;">{{$product->brand->description}}</p></th>
                                                        <th><input type="number" id="amount-{{$product->id}}" class="form-control"></th>
                                                        <th><input type="number" id="price-{{$product->id}}" class="form-control"></th>
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

    var arraySelects = [''];
    var ultimoValorSeleccionado = [];
    var reiniciarAutocompletado = [];


    arraySelects.forEach((element, index) => { 
        // Función para reiniciar el autocompletado
        reiniciarAutocompletado[index] = () => {
          $(`#myUL${element} li`).show(); // Mostrar todas las opciones
        }
      
        // Mostrar la lista al hacer clic en el input
        $(`#myInput${element}`).click(function() {
          $(`#myUL${element}`).show();
          reiniciarAutocompletado[index](); // Reiniciar autocompletado al hacer clic en el input
        });
        
        // Seleccionar una opción de la lista
        $(`#myUL${element}`).on("click", "li", function() {
          var value = $(this).text();
          $(`#myInput${element}`).val(value); // Colocar el valor seleccionado en el input
          ultimoValorSeleccionado[index] = value; // Actualizar el último valor seleccionado
          $(`#myUL${element}`).hide(); // Ocultar la lista después de seleccionar
        });
        
        // Filtrar opciones según la entrada del usuario
        $(`#myInput${element}`).on("input", function() {
          reiniciarAutocompletado[index](); // Reiniciar autocompletado al escribir en el input
          var value = $(this).val().toLowerCase();
          $(`#myUL${element} li`).each(function() {
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
          var inputValue = $(`#myInput${element}`).val();
          if(!$target.closest('.autocomplete').length) {
            if (inputValue !== ultimoValorSeleccionado[index]) {
              $(`#myInput${element}`).val(""); // Vaciar el input si no se seleccionó una opción recientemente
            }
            $(`#myUL${element}`).hide(); // Ocultar la lista en cualquier caso
          }
        });
    });

    function seleccionarCiudad(id){
        $("#store-id").val(id);
    }

    $(document).ready(function() {
        // Agregar un event listener para el cambio de estado de todos los checkboxes con la clase 'myCheckbox' usando jQuery
        $('.myCheckbox').change(function() {
            // Obtener el ID del checkbox seleccionado
            var checkboxId = $(this).data('id');
            
            // Obtener el valor actual del input oculto
            var selectedIds = $('#selectedIds').val().split(',');
            
            // Verificar si el checkbox está seleccionado
            if ($(this).is(':checked')) {
            // Agregar el ID al arreglo de IDs seleccionados
            selectedIds.push(checkboxId);
            } else {
                // Quitar el ID del arreglo de IDs seleccionados
                var index = selectedIds.indexOf(checkboxId.toString());
                if (index !== -1) {
                    selectedIds.splice(index, 1);
                }
            }
        
            // Actualizar el valor del input oculto
            $('#selectedIds').val(selectedIds.join(','));
        });
    });

    $("#associate").click(() => {
        validateData();
    });

    function validateData(){
        if($("#store-id").val() == '' || $("#store-id").val() == null){
            Swal.fire({
                    title: "Debes seleccionar una tienda",
                    icon: "error",
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            return false;
        }

        if($('#selectedIds').val() == ''){
            Swal.fire({
                    title: "Debes seleccionar por lo menos un producto",
                    icon: "error",
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            return false;
        }

        var array = $('#selectedIds').val().split(',');
        array.shift();
        validateDataProducts(array);
    }

    function validateDataProducts(array){
        var error = false;
        var amounts = [];
        var prices = [];
        array.forEach(element => {
            amounts.push($(`#amount-${element}`).val());
            prices.push($(`#price-${element}`).val());
            if($(`#amount-${element}`).val() == '' || $(`#price-${element}`).val() == ''){
                error = true;
            }
        });

        if(error){
                Swal.fire({
                    title: "Asegurate de ingresar todos los precios y cantidades",
                    icon: "error",
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            return false;
        } 

        storeData(amounts, prices);
    }

    function storeData(amounts, prices){
        $('#amounts').val(amounts.join(','));
        $('#prices').val(prices.join(','));
        $("#form").submit();
    }

    </script>
</body>

</html>