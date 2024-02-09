<x-app-layout>
    @section('css')
    <style>
         .card {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombras en el borde */

        }
        .card-img {
            flex: 0 0 auto;
            width: 50%;
            overflow: hidden;
         }
        .card-text-container {
            flex: 1 1 auto;
            overflow: auto;
            padding: 1rem;
        }

        .dropdown-toggle::after{
            margin-left: .4rem !important;
            margin-top: .3rem !important;
        }
    </style>
    @endsection

    <div class="row">
        <div class="container" style="padding-inline: 12vw !important;padding-top: 3vw;">
            <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-6">
                    <img style="object-fit: cover;
                    width: 100%;
                    height: 20rem;" src=" {{ asset($publicity->image) }} " class="img-fluid rounded-start" alt="...">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 col-md-9">
                                <a href="{{ asset('tienda/'.$publicity->link) }}"><p style="font-size: .74rem;text-decoration: underline;color: #666;">{{ asset('tienda/'.$publicity->link) }}</p></a>
                            </div>
                            <form action=" {{ route('subscribe') }} " method="POST" id="subscribe-form" class="d-none">
                                @csrf
                                <input type="hidden" name="id" value="{{ $store->id }}">
                                <input type="hidden" name="id_p" value="{{ $publicity->id }}">
                            </form>
                            <form action=" {{ route('unsubscribe') }} " method="POST" id="unsubscribe-form" class="d-none">
                                @csrf
                                <input type="hidden" name="id" value="{{ $store->id }}">
                                <input type="hidden" name="id_p" value="{{ $publicity->id }}">
                            </form>
                            <div class="col-4 col-md-3">
                                @if(!$subscribed)
                                    <button class="btn btn-primary" id="subscribe" style="font-size: .6rem;display: flex;"><i class="fa-solid fa-bell me-1" style="margin-top: .2rem;"></i>Suscribete</button>
                                @else
                                    <div class="dropdown">
                                        <button class="btn  btn-primary dropdown-toggle" style="font-size: .6rem;display: flex;" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-bell me-1" style="margin-top: .2rem;"></i>Suscrito
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" id="unsubscribe">Anular</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title">{{ $publicity->title }}</h5>
                                <p class="card-text">
                                    {{ $publicity->description }}                                
                                </p>                            
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row text-center pb-3">
            <h4>Más publicidad</h4>
        </div>

        <div class="row px-5 pb-5">
            @foreach ($publicities as $key)
                <div class="col-md-4 mt-3">
                    <div class="card" style="border-radius: 15px;">
                        <div class="contenedor-imagen" onclick="goPagePublicity({{$key->id}})" style="width: 100%;">
                            <img src="{{ asset($key->image) }}" style="width: 100% !important;
                            height: 12rem !important;
                            object-fit: cover;
                          " class="img-fluid imagen-zoom" alt="Imagen 1">
                            <div class="texto-encima">{{ $key->title }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
</x-app-layout>