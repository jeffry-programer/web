<div>
    <div class="row">
        <div class="col-md-1 offset-md-6 d-flex align-items-center mt-3">
            @if ($subscribed)
                <button type="button" class="btn btn-subs" style="display: flex;align-items: center;" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Suscrito
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="cursor: pointer">
                    <li><a class="dropdown-item" wire:click="nullSubscribe">Anular</a></li>
                </ul>
            @else
                @if (!$condition)
                    <button class="btn btn-subs" wire:click="subscribe">Suscribete</button>
                @endif
            @endif
        </div>
        @if (!$condition2)
            <div class="col-12 col-md-5">
                <?php
                $link_store = str_replace(' ', '-', $store->name);
                ?>
                <form action="/tienda/{{ $link_store }}" class="row mt-3" id="search-data-store" autocomplete="off"
                    style="background: #fff;
                    border-radius: 30px;
                    border: solid 1px #dfdfdf;
                    box-shadow: 1px 0px 6px 2px rgba(39,39,39,0.16);">
                    <div class="col-4 col-md-3 d-none d-md-flex align-items-center justify-content-center">
                        <select id="select-search-categories" name="tBGZall1t5CCeUqrQOkM" class="select-search">
                            <option selected>Categoria</option>
                            @foreach ($categories as $key)
                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-10 col-md-7 d-flex align-items-center justify-content-center">
                        <input class="input-search" name="product" placeholder="Busca el repuesto o accesorio"
                            type="text">
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass icons-search pointer"></i></button>
                        <button type="button"><i class="fa-solid fa-microphone icons-search pointer"></i></button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
