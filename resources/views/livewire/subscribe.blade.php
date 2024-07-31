<style>
    /*----------------------------------------------------------------------------------*/
    #myInput97 {
        border: none;
    }

    #myUL97 {
        list-style-type: none;
        padding: 0;
        margin: 0;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: none;
        width: 100%;
        z-index: 1000;
        max-height: 150px;
        /* Altura máxima para el scroll */
        overflow-y: auto;
        /* Habilitar el scroll vertical */
        display: block;
        /* Ocultar la lista inicialmente */
    }

    #myUL97 li {
        cursor: pointer;
    }

    #myUL97 li a {
        padding: 10px;
        display: block;
        text-decoration: none;
        color: #000;
    }

    #myUL97 li a:hover {
        background-color: #f4f4f4;
    }
</style>


<div>
    <div class="row">
        <div class="col-md-1 offset-md-6 d-flex align-items-center mt-3">
            @if ($subscribed)
                <button type="button" class="btn btn-subs" style="display: flex;align-items: center;"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Suscrito
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="cursor: pointer">
                    <li><a class="dropdown-item" id="nullSubscribe">Anular</a></li>
                </ul>
            @else
                @if (!$condition)
                    <button class="btn btn-subs" id="subscribe">Suscribete</button>
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
                            <option selected value="Categoria">Categoria</option>
                            @foreach ($categories as $key)
                                @if($category != 'Categoria' && $key->id == $category)
                                    <option value="{{ $key->id }}" selected>{{ $key->name }}</option>
                                @else
                                    <option value="{{ $key->id }}">{{ $key->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-10 col-md-7 d-flex align-items-center justify-content-center">
                        <div class="autocomplete">
                            <input class="input-search" name="product" id="myInput97" placeholder="Busca en esta tienda"
                                type="text" value="{{ $search }}">
                            <ul id="myUL97">
                            </ul>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <button type="submit"><i
                                class="fa-solid fa-magnifying-glass icons-search pointer"></i></button>
                        <button type="button"><i class="fa-solid fa-microphone icons-search pointer"></i></button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#myInput97').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('autocomplete-products-store') }}",
                    method: "GET",
                    data: {
                        term: query,
                        store: '{{ $store->id }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#myUL97').empty();
                        $.each(data, function(key, value) {
                            $('#myUL97').append(
                                '<li class="list-item" data-name="' + value
                                .name + '"><a>' + value.name + '</a></li>');
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.list-item', function() {
        var name = $(this).data('name');
        $('#myInput97').val(name);
        $('#myUL97').empty();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#myUL97').length && !$(e.target).is('#myUL97')) {
            $('#myUL97').empty();
        }
    });

    $("#subscribe").click(() => {
        subscribeUser();
    });

    $("#nullSubscribe").click(() => {
        nullSubscribeUser();
    });

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

    function subscribeUser() {
        showAlertTime();
        $.ajax({
            url: "{{ route('subscribe-user') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            method: "POST",
            data: {
                stores_id: '{{ $store->id }}'
            },
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });
    }

    function nullSubscribeUser() {
        showAlertTime();
        $.ajax({
            url: "{{ route('null-subscribe') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            method: "POST",
            data: {
                stores_id: '{{ $store->id }}'
            },
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });
    }
</script>
