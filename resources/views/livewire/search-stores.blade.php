<div>
    @if(!$empty_stores)
        <div class="alert alert-info p-3 text-center alert-info-search-stores">
            Las siguientes tiendas tienen el repuesto "{{ str_replace('+', ' ', $product_search) }}", entra a la que desees y contacta al vendedor
        </div>
        @if($search_found != 'cities' && $search_found != '')
            <div class="alert alert-warning p-3 text-center alert-info-search-stores">
                No hemos encontrado productos para esta ciudad, aqui que te mostramos resultados para el {{ $search_found }}
            </div>
        @endif
        <h2 class="ms-5 mt-5">Tiendas que tienen el producto</h2>
    @endif
    @if(!$empty_stores)
        <div class="container">
            <div class="row">
                @foreach ($stores as $store)
                    <div class="col-12 col-md-4 mt-3">
                    <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{$store->link}}">
                        <div class="card card-store">
                            <div class="zoom-container">
                            <img class="zoomed-image" src="{{ asset($store->image) }}" alt="Descripción de la imagen">
                            </div>
                            <div class="card-body" style="padding-bottom: 4rem;">
                            <h5 class="card-title">{{$store->name}}</h5>
                            <p class="card-text">{{$store->description}}</p>
                            <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 18rem;"><i class="fa-solid fa-location-dot me-1"></i>{{$store->city}} - {{$store->address}}</p>
                            <a href="/tienda/{{ str_replace(' ','-', $store->name) }}/{{$store->link}}" class="btn btn-warning position-absolute bottom-0 end-0" style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                            </div>
                        </div>
                    </a>
                    </div>
                @endforeach
                {{ $stores->links('custom-pagination-links-view') }}
            </div>
        </div>
    @endif
    @if($empty_stores)
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
