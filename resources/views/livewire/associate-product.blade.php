<div>
    <div class="modal-body">
        <div>
            <div class="alert alert-info">Puedes asociar un producto ya existente o crear uno nuevo</div>
        </div>
        <form id="form" style="display: contents;">
            <input type="hidden" name="image" value="">
            <input type="hidden" name="count" value="0">
            <input type="hidden" name="link" value="0">
            <input type="hidden" name="type_request" value="asociate">
            <div class="row">
                <div class="col-12 form-group products">
                    <label>Nombre del producto</label>
                    <input type="hidden" name="cities_id" id="city_store_data_id">
                    <div class="autocomplete">
                        <input class="form-select mt-3" type="text" id="myInput30" name="name"
                            placeholder="Busca y selecciona un producto..." autocomplete="off">
                        <ul id="myUL30">
                            @foreach ($products as $product)
                                <li><a onclick="selectCity30({{ $product->id }})">{{ $product->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row d-none nextSteep">
                <div class="col-md-6 form-group">
                    <label for="">Categorias</label>
                    <select class="form-select my-3" id="select1" data-type="category">
                        <option value="" selected>Seleccione una categoria</option>
                        @foreach ($categories as $key)
                            <option value="{{ $key->id }}">{{ $key->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Sub Categoria</label>
                    <select class="form-select my-3" id="select2" name="sub_categories_id">
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
                <div class="col-md-12 form-group">
                    <label>Detalle</label>
                    <input type="form-control" name="detail" class="form-control my-3"
                        placeholder="Ingrese un detalle">
                </div>
            </div>
            <div class="row d-none" id="product_store_row">
                <input type="hidden" name="stores_id" value="{{ $store->id }}">
                <input type="hidden" name="products_id" id="product_id">
                <div class="col-md-6 form-group">
                    <label>Cantidad</label>
                    <input type="number" name="amount" wire:model="amount" placeholder="Ingrese la cantidad"
                        min="1" class="form-control w-100 mt-3">
                </div>
                <div class="col-md-6 form-group">
                    <label>Precio</label>
                    <input type="number" name="price" wire:model="price" placeholder="Ingrese el precio"
                        min="1" class="form-control w-100 mt-3">
                </div>
            </div>
            <div class="row nextSteep d-none">
                <div class="col-md-12 form-group">
                    <label class="py-3" for="name">{{ __('Imagenes') }}</label>
                    <div class="dropzone" id="myDropzone24">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <input type="hidden" id="id_table">
    <input type="hidden" id="table" value="products">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="next">Siguiente</button>
        <button type="button" class="btn btn-primary d-none" id="store">Asociar</button>
    </div>
</div>

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
                        console.log(data);
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
