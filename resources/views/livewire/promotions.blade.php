<div>
    <div class="row" style="border-bottom: solid 0.15rem #dee2e6;padding: 1rem;">
        <h4 class="ms-3">Agregar promoción a producto</h4>
    </div>
    <div>

        @if (session()->has('message'))

            <div class="alert alert-success">

                {{ session('message') }}

            </div>

        @endif

    </div>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-4 form-group">
                <label for="">Nombre del producto</label>
                <div class="autocompletar">
                    <input type="text" class="form-control w-100 mt-3" placeholder="Por favor ingrese un nombre" id="product" wire:model="productPromotionInput" wire:keydown="search">
                    @error('productPromotionInput') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                    <div class="lista-autocompletar-items w-100" style="left: 0%">
                        @foreach ($dataProductsPromotion as $key)
                            <div wire:click="select('{{ $key->name }}', {{ $key->id }});"><strong>{{ substr($key->name, 0, strlen($productPromotionInput)) }}</strong>{{ substr($key->name, strlen($productPromotionInput)) }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" wire:model="id_product_store" id="idProductPromotion">
                </div>
                <label for="" class="mt-3">Fecha inicio</label>
                <input type="date" wire:model="date_init" class="form-control w-100 mt-3">
                @error('date_init') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label for="" class="mt-3">Porcentaje promoción</label>
                <input type="number" wire:model="percent_promotion" min="1" max="100" class="form-control w-100 mt-3" placeholder="Por favor ingrese un precio">
                @error('percent_promotion') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label for="" class="mt-3">Descripción oferta</label>
                <textarea class="form-control mt-3 w-100" wire:model="description_promotion" rows="5" placeholder="Escribe aquí..."></textarea>
                @error('description_promotion') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-md-4 offset-md-2 form-group">
                <label for="">Precio promoción</label>
                <input type="number" wire:model="price_promotion" class="form-control w-100 mt-3" placeholder="Por favor ingrese un precio">
                @error('price_promotion') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label for="" class="mt-3">Fecha fin</label>
                <input type="date" wire:model="date_end" class="form-control w-100 mt-3">
                @error('date_end') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label for="" class="mt-3">Adjuntar imagen</label>
                <input type="file" wire:model="image_promotion" class="form-control w-100 mt-3">
                @error('image_promotion') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-12 col-md-4 offset-md-6">
                <button class="btn btn-primary mt-3 w-100" @if($id_product_store == null) disabled @endif id="savePromotion" wire:click="savePromotion">Guardar</button>
            </div>
        </div>
    </div>
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
        <div class="row mt-3">
            <div class="col-md-4 form-group">
                <label for="">Descripción oferta</label>
                <input type="text" wire:model="description_ofer" class="form-control w-100 mt-3" placeholder="Por favor ingrese una descripción">
                @error('description_ofer') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label class="mt-3">Titulo de la publicidad</label>
                <input type="text" wire:model="title" class="form-control w-100 mt-3" placeholder="Por favor escriba un titulo">
                @error('title') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-md-4 offset-md-2 form-group">
                <label for="">Adjuntar imagen</label>
                <input type="file" wire:model="image" class="form-control w-100 mt-3">
                @error('image') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label class="mt-3">Link de la pagina</label>
                <input type="text" wire:model="link" class="form-control w-100 mt-3" placeholder="Por favor escriba el link de la pagina">
                @error('link') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-12 col-md-4 offset-md-6">
                <button class="btn btn-primary mt-3 w-100" wire:click="savePublicity">Guardar</button>
            </div>
        </div>
    </div>
</div>
