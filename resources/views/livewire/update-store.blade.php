<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Nombre de la tienda</label>
                <input type="text" class="form-control mt-3 mb-3" wire:model="name" placeholder="Ingrese un nombre">
                @error('name') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label>Dirección</label>
                <input type="text" class="form-control mt-3 mb-3" wire:model="address" placeholder="Ingrese una dirección">
                @error('address') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label>Descripción</label>
                <input type="text" class="form-control mt-3 mb-3" wire:model="description" placeholder="Ingrese una descripción">
                @error('description') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Correo electronico</label>
                <input type="email" class="form-control mt-3 mb-3" wire:model="email" placeholder="Ingrese un correo">
                @error('email') <span class="error text-danger">{{ $message }}</span> <br>@enderror
                <label>Numeros de contacto</label>
                <input type="number" class="form-control mt-3" wire:model="phone" placeholder="Ingrese un numero">
                @error('phone') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" wire:click="updateStore">Guardar</button>
    </div>
</div>

