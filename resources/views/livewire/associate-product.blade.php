@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endsection

<div>
    <div class="modal-body">
        <div class="row">
            @if($nextSteep == false)
                <div>
                    <div class="alert alert-info">Puedes asociar un producto ya existente o crear uno nuevo</div>
                </div>
                <div class="col-12 form-group">
                    <label>Nombre del producto</label>
                    <div class="autocompletar">
                        <input type="text" class="form-control w-100 mt-3" placeholder="Por favor ingrese un nombre" id="product" wire:model="productInput" wire:keydown="search">
                        @error('productPromotionInput') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                        <div class="lista-autocompletar-items w-100" style="left: 0%">
                            @foreach ($dataProducts as $key)
                                <div wire:click="select('{{ $key->name }}', {{ $key->id }});"><strong>{{ substr($key->name, 0, strlen($productInput)) }}</strong>{{ substr($key->name, strlen($productInput)) }}</div>
                            @endforeach
                        </div>
                        <input type="hidden" wire:model="product_id">
                    </div>
                </div>
            @else
                @if($product_id == null)
                    <div class="col-md-6 form-group">
                        <label>Sub categoria</label>
                        <select class="form-select my-3">
                            @foreach ($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Cilindraje</label>
                        <select class="form-select my-3">
                            @foreach ($cylinder_capacities as $cylinder_capacity)
                                <option value="{{$cylinder_capacity->id}}">{{$cylinder_capacity->description}}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Modelo</label>
                        <select class="form-select my-3">
                            @foreach ($models as $model)
                                <option value="{{$model->id}}">{{$model->description}}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Caja</label>
                        <select class="form-select my-3">
                            @foreach ($boxes as $box)
                                <option value="{{$box->id}}">{{$box->description}}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tipo producto</label>
                        <select class="form-select my-3">
                            @foreach ($type_products as $type_product)
                                <option value="{{$type_product->id}}">{{$box->description}}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Marca</label>
                        <select class="form-select my-3">
                            @foreach ($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->description}}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Descripción</label>
                        <input type="form-control" class="form-control my-3" placeholder="Ingrese una descripción">                 
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Código</label>
                        <input type="form-control" class="form-control my-3" placeholder="Ingrese un código">                 
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Referencia</label>
                        <input type="form-control" class="form-control my-3" placeholder="Ingrese una referencia">                 
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Detalle</label>
                        <input type="form-control" class="form-control my-3" placeholder="Ingrese un detalle">                 
                    </div>
                @endif
                <div class="col-md-6 form-group">
                    <label>Cantidad</label>
                    <input type="number" wire:model="amount" placeholder="Ingrese la cantidad" class="form-control w-100 mt-3">
                </div>
                <div class="col-md-6 form-group">
                    <label>Precio</label>
                    <input type="number" wire:model="price" placeholder="Ingrese el precio" class="form-control w-100 mt-3">
                </div>  
                @if($product_id == null)
                    <div class="col-12 form-group">
                        <label class="mt-3">Imagenes</label>
                        <div class="dropzone" id="myDropzone"></div>
                    </div> 
                    <input type="hidden" id="maxFiles" value="5">
                @endif
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        @if($nextSteep == false)
        <button type="button" class="btn btn-primary" wire:click="associate">Siguiente</button>
        @else
        <button type="button" class="btn btn-primary" wire:click="associate">Asociar</button>
        @endif
    </div>
</div>

@section('js')
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
            maxFilesize : 5,
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
    </script>
@endsection
