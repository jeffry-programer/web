<div>
    @php
        $search = str_replace(['+','"'], [' ',''], $product_search);
    @endphp
    @if(count($stores) > 0)
        @if($locationStores == 'sector')
            <div class="alert alert-warning p-3 text-center alert-info-search-stores">
                Las siguientes tiendas tienen el repuesto "{{ $search }}", entra a la que desees y contacta al vendedor
            </div>
        @endif
        @if($locationStores == 'municipality')
            <div class="alert alert-warning p-3 text-center alert-info-search-stores">
                No hemos encontrado productos para este sector, aqui que te mostramos resultados para tu municipio
            </div>
            <div class="alert alert-info p-3 text-center alert-info-search-stores">
                Las siguientes tiendas tienen el repuesto "{{ $search }}", entra a la que desees y contacta al vendedor
            </div>
        @endif
        @if($locationStores == 'state')
            <div class="alert alert-warning p-3 text-center alert-info-search-stores">
                No hemos encontrado productos para este municipio, aqui que te mostramos resultados para tu estado
            </div>
            <div class="alert alert-info p-3 text-center alert-info-search-stores">
                Las siguientes tiendas tienen el repuesto "{{ $search }}", entra a la que desees y contacta al vendedor
            </div>
        @endif
        @if($locationStores == 'country')
            <div class="alert alert-warning p-3 text-center alert-info-search-stores">
                No hemos encontrado productos para este estado, aqui que te mostramos resultados para tu país
            </div>
            <div class="alert alert-info p-3 text-center alert-info-search-stores">
                Las siguientes tiendas tienen el repuesto "{{ $search }}", entra a la que desees y contacta al vendedor
            </div>
        @endif
        <h2 class="ms-5 mt-5">Tiendas que tienen el producto</h2>
    @endif
    @if(count($stores) > 0)
        <div class="container">
            <div class="row">
                @foreach ($stores as $store)
                    <div class="col-12 col-md-4 mt-3">
                    <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{ str_replace(' ','-', $store->products->first()->name) }}">
                        <div class="card card-store">
                            <div class="zoom-container">
                            <img class="zoomed-image" src="{{ asset($store->image) }}" alt="Descripción de la imagen">
                            </div>
                            <div class="card-body" style="height: 14rem;">
                            <h5 class="card-title">{{$store->name}}</h5>
                            <p class="card-text">{{$store->description}}</p>
                            <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 80%;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;white-space: normal;"><i class="fa-solid fa-location-dot me-1"></i>{{$store->municipality->name}} - {{ $store->sector->description }} - {{$store->address}}</p>
                            <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{ str_replace(' ','-', $store->products->first()->name) }}" class="btn btn-warning position-absolute bottom-0 end-0" style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                            </div>
                        </div>
                    </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if(count($stores) == 0)
        <div class="row">
            <div class="col-12 col-md-6">
                <h2 class="ms-5 mt-5">Ups no hemos encontrado resultados a tu búsqueda</h2>
            </div>
            <div class="col-12 col-md-6">
                <img src="{{ asset('images/store.png') }}" class="img-fluid" alt="">
            </div>
        </div>
    @endif
</div>

@section('js')
<script>
    $(".input-search").val("{{ $search }}");
</script>
@endsection