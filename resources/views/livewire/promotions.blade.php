<div>
    @if(!$condition2)
    <div class="row" style="border-bottom: solid 0.15rem #dee2e6;padding: 1rem;">
        <h4 class="ms-3">Agregar promoción a producto</h4>
    </div>
    <div>
        <div class="alert alert-info">
            Debes escribir y seleccionar un producto que ya este asociado a tu tienda para poder crear una promoción
        </div>
    </div>
    <div>

        @if (session()->has('message'))

            <div class="alert alert-success">

                {{ session('message') }}

            </div>

        @endif

    </div>
    <div class="container">
        <form id="form-promotion">
            <div class="row mt-3">
                <div class="col-md-4 form-group">
                    <label class="mb-3">Nombre del producto</label>
                    <input type="hidden" name="product_stores_id" id="product_stores_id">
                    <div class="autocomplete">
                        <input class="form-select" type="text" id="myInput5" name="name" placeholder="Selecciona un producto de tu tienda...">
                        <ul id="myUL5">
                            @foreach ($products as $product)
                                <li><a onclick="seleccionarProductoPromocion({{$product->id}})">{{$product->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <label for="" class="mt-3">Fecha inicio</label>
                    <input type="date" name="date_init" class="form-control w-100 my-3">
                    <label for="">Fecha fin</label>
                    <input type="date" name="date_end" class="form-control w-100 mt-3">
                    <label for="" class="mt-3">Descripción oferta</label>
                    <textarea class="form-control mt-3 w-100" name="description" rows="5" placeholder="Por favor ingrese una descripción"></textarea>
                </div>
                <div class="col-md-4 offset-md-2 form-group">
                    <label >Porcentaje promoción</label>
                    <input type="number" name="percent_promotion" min="1" max="100" class="form-control w-100 mt-3" placeholder="Por favor ingrese un precio">
                    <label class="py-3" for="name">{{ __('Imagen') }}</label>
                    <div class="dropzone" id="myDropzone82"></div>
                    <input type="hidden" id="id_promotion_save">
                </div>
                <div class="col-12 col-md-4 offset-md-6">
                    <button type="button" class="btn btn-primary mt-3 w-100" disabled id="save-promotion">Guardar</button>
                </div>
            </div>
        </form>
    </div>
    @endif
    <div class="row" style="border-bottom: solid 0.15rem #dee2e6;padding: 1rem;">
        <h4 class="ms-3">Agregar publicidad de la tienda</h4>
    </div>
    <div>
        @if (session()->has('message2'))
            <div class="alert alert-success">
                {{ session('message2') }}
            </div>
        @endif
    </div>
    <div class="container">
        <form id="form-publicity">
            <div class="row mt-3">
                <div class="col-md-4 form-group">
                    <input type="hidden" name="stores_id" value="{{$global_store->id}}">
                    <label for="">Tipo de publicidad</label>
                    <select class="form-select mt-3" name="type_publicities_id">
                        <option value="">Seleccione un tipo de publicidad</option>
                        @foreach ($type_publicities as $key)
                            <option value="{{$key->id}}">{{$key->description}}</option>
                        @endforeach
                    </select>
                    <label class="mt-3">Titulo de la publicidad</label>
                    <input type="text" name="title" class="form-control w-100 mt-3" placeholder="Por favor escriba un titulo">
                </div>
                <div class="col-md-4 offset-md-2 form-group">
                    <label for="" class="mt-3">Descripción de la publicidad</label>
                    
                    <textarea class="form-control" name="description" class="form-control w-100 mt-3" placeholder="Por favor ingrese una descripción" rows="3"></textarea>

                    <label class="py-3" for="name">{{ __('Imagen') }}</label>
                    <div class="dropzone" id="myDropzone92"></div>
                    <input type="hidden" id="id_publicities_save">
                </div>
                <div class="col-12 col-md-4 offset-md-6">
                    <button type="button" class="btn btn-primary mt-3 w-100" id="save-publicity">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $('#limpiarInputs1').click(function() {
        // Limpia el valor de los inputs de tipo archivo
        setTimeout(() => {
            $('#fileInput1').val('');
        }, 1500);
    });

    $('#limpiarInputs2').click(function() {
        // Limpia el valor de los inputs de tipo archivo
        setTimeout(() => {
            $('#fileInput2').val('');
            /*$("#title").val('');
            $("#description_ofer").val('');
            $("#type_publicity").val('');*/
        }, 1500);
    });
</script>
