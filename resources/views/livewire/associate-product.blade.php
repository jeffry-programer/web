<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                    <div class="col-12 form-group @if($nextSteep != false) d-none @endif">
                        <label>Nombre del producto</label>
                        <div class="autocompletar">
                            <input type="text" class="form-control w-100 mt-3" placeholder="Por favor ingrese un nombre" id="product" name="name" wire:model="productInput" wire:keydown="search">
                            @error('productPromotionInput') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                            <div class="lista-autocompletar-items w-100" style="left: 0%">
                                @foreach ($dataProducts as $key)
                                    <div wire:click="select('{{ $key->name }}', {{ $key->id }});"><strong>{{ substr($key->name, 0, strlen($productInput)) }}</strong>{{ substr($key->name, strlen($productInput)) }}</div>
                                @endforeach
                            </div>
                            <input type="hidden" wire:model="product_id">
                        </div>
                    </div>
                @if($nextSteep != false)
                    @if($product_id == null)
                        <div class="col-md-6 form-group">
                            <label>Sub categoria</label>
                            <select class="form-select my-3" name="sub_categories_id">
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Cilindraje</label>
                            <select class="form-select my-3" name="cylinder_capacities_id">
                                @foreach ($cylinder_capacities as $cylinder_capacity)
                                    <option value="{{$cylinder_capacity->id}}">{{$cylinder_capacity->description}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Modelo</label>
                            <select class="form-select my-3" name="models_id">
                                @foreach ($models as $model)
                                    <option value="{{$model->id}}">{{$model->description}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Caja</label>
                            <select class="form-select my-3" name="boxes_id">
                                @foreach ($boxes as $box)
                                    <option value="{{$box->id}}">{{$box->description}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tipo producto</label>
                            <select class="form-select my-3" name="type_products_id">
                                @foreach ($type_products as $type_product)
                                    <option value="{{$type_product->id}}">{{$box->description}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Marca</label>
                            <select class="form-select my-3" name="brands_id">
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}">{{$brand->description}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Descripción</label>
                            <input type="form-control" name="description" class="form-control my-3" placeholder="Ingrese una descripción">                 
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Código</label>
                            <input type="form-control" name="code" class="form-control my-3" placeholder="Ingrese un código">                 
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Referencia</label>
                            <input type="form-control" name="reference" class="form-control my-3" placeholder="Ingrese una referencia">                 
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Detalle</label>
                            <input type="form-control" name="detail" class="form-control my-3" placeholder="Ingrese un detalle">                 
                        </div>
                    @endif
                    <input type="hidden" name="stores_id" value="{{$store->id}}">
                    <div class="col-md-6 form-group">
                        <label>Cantidad</label>
                        <input type="number" name="amount" wire:model="amount" placeholder="Ingrese la cantidad" class="form-control w-100 mt-3">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Precio</label>
                        <input type="number" name="price" wire:model="price" placeholder="Ingrese el precio" class="form-control w-100 mt-3">
                    </div>  
                @endif
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary @if($nextSteep != false) d-none @endif" wire:click="associate">Siguiente</button>
        <button type="button" class="btn btn-primary @if($nextSteep == false) d-none @endif" id="store">Asociar</button>
    </div>
</div>

@section('js')
    <script>
        $("#store").click((e) => {
            validateDataStore();
        });

        function validateDataStore(){
            var data = $("#form").serialize().split('&');
            var boolean = true;
            data.forEach((key) => {
                let value = key.split('=')[1];
                let field = key.split('=')[0];
                if(field.includes('link')) return false;
                if(field.includes('image')) return false;
                if(field.includes('count')) return false;
                if(value == null || value == ''){
                    boolean = false;
                }
            });

            if(!boolean){
                Swal.fire({
                    title: "Campos ingresados no válidos",
                    timer: 2000,
                    timerProgressBar: true,
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

        function storeData(){
            $.ajax({
                url: "{{route('table-store-imgs-3')}}",
                data: $("#form").serialize(),
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN' : "{{csrf_token()}}",
                },
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
                },error: function(xhr) {
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
    </script>
@endsection
