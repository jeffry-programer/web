<div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Cantidad</label>
                <input type="number" class="form-control mt-3 mb-3" wire:model="amount" min="0" placeholder="Ingrese una cantidad">
                @error('amount') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Precio</label>
                <input type="number" class="form-control mt-3 mb-3" wire:model="price" min="0" placeholder="Ingrese un precio">
                @error('price') <span class="error text-danger">{{ $message }}</span> <br>@enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" wire:click="updateProduct">Guardar</button>
    </div>
</div>
