<div>
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100" style="height: 6rem;height: 6rem;border: solid 0px;border-bottom: solid 1.4rem #6495ED;padding-bottom: 6rem;margin-bottom: 0rem;">
        <div class="row pt-3" style="width: 100%;">
            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="font-size: 1.2rem;
                border: solid 1px #dfdfdf;">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
              
              <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                  <h5 class="offcanvas-title" id="offcanvasExampleLabel">Tulobuscas</h5>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Otras opciones') }}
                    </div>

                    <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal25" style="text-decoration: none">
                        <i class="fa-solid fa-user me-1"></i>{{ __('Suscripciones') }}
                    </x-dropdown-link>

                    <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal26" style="text-decoration: none">
                        <i class="fa-solid fa-circle-user me-1"></i>{{ __('Tiendas') }}
                    </x-dropdown-link>

                    
                    <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal27" style="text-decoration: none">
                        <i class="fa-solid fa-house me-1"></i>{{ __('Talleres') }}
                    </x-dropdown-link>
              </div>
            <div class="col-10 col-md-3 d-flex align-items-center justify-content-center">
                <img class="img-fluid" src=" {{ asset('images/tulobuscas.png') }} " alt="img" style="cursor: pointer;" onclick="window.location.replace('/');">
            </div>
            <div class="col-2 d-flex d-md-none align-items-center justify-content-center">
              <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                  <i class="fa-solid fa-bars"></i>
              </button>
              <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    @if(Auth::user())
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">{{ Auth::user()->name }}</h5>
                    @else
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Tulobuscas</h5>
                    @endif
                  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    @if(Auth::user())
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('profile.show') }}"><i class="fa-solid fa-user-plus me-2"></i>Perfil</a>
                      </li>
                      <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button class="nav-link active" aria-current="page"><i class="fa-solid fa-right-to-bracket me-2"></i>Cerrar sesión</button>
                        </form>
                      </li>
                    @else
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('registro') }}"><i class="fa-solid fa-user-plus me-2"></i>Registro</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar sesión</a>
                      </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-12 d-flex col-md-5 align-items-center justify-content-center">
                <form action="{{ route('search-stores') }}" class="row" id="form-search" autocomplete="off">
                    <input type="hidden" id="value-country" name="iSVBGR6m3mmQdQRQCa">  
                    <input type="hidden" id="value-state" name="DPLY40rNOyz0hl">  
                    <input type="hidden" id="value-city" name="I9CLmGfm0ppURDM">   
                    <div class="col-2 col-md-3 d-flex align-items-center justify-content-center">
                        <button type="button" style="display: flex;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="select-search"><i class="fa-solid fa-location-dot me-3 my-auto"></i><span class="d-none d-md-flex" id="btn-ubi">Ubicacion</span></button>
                    </div> 
                    <div class="col-2 d-none d-md-flex align-items-center justify-content-center">
                        <select id="select-search-categories" name="tBGZall1t5CCeUqrQOkM" class="select-search">
                            <option selected>Categoria</option>
                            @foreach ($categories as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="col-8 col-md-6 d-flex align-items-center justify-content-center">
                        <input class="input-search" name="product" placeholder="Busca y compara productos" type="text">
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-magnifying-glass icons-search pointer" onclick="searchData()"></i>
                      <i class="fa-solid fa-microphone icons-search pointer" style="margin-left: -1rem;"></i>
                    </div>
                </form>            
            </div>
            <div class="d-none d-md-flex col-md-3 align-items-center justify-content-center">
                @if(Auth::user())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <?php
                                    $url_profile = asset(Auth::user()->profile_photo_url);
                                ?>
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $url_profile }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        </x-slot>
                    
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manejar cuenta') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}" style="text-decoration: none">
                                <i class="fa-solid fa-user me-1"></i>{{ __('Perfil') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="/admin/table-management/Cajas" style="text-decoration: none">
                                <i class="fa-solid fa-circle-user me-1"></i>{{ __('Adminstación') }}
                            </x-dropdown-link>

                            
                            <x-dropdown-link href="/tienda/{{ $link_store }}" style="text-decoration: none">
                                <i class="fa-solid fa-house me-1"></i>{{ __('Mi tienda') }}
                            </x-dropdown-link>
                    
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif
                    
                    <div class="border-t border-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" style="text-decoration: none">
                            <i class="fa-solid fa-right-from-bracket me-1"></i>{{ __('Cerrar sesion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
              @else
                  @if (Route::has('login'))
                      <div class="row">
                        <div class="col-12 col-lg-6">
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" style="text-decoration: none"><i class="fa-solid fa-right-to-bracket me-1"></i>Iniciar sesión</a>
                        </div>
                        <div class="col-12 mt-2 col-lg-6 mt-lg-0">
                            <a href="{{ route('registro') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" style="text-decoration: none"><i class="fa-solid fa-user-plus me-1"></i>Registro</a>
                        </div>
                      </div>
                  @endif
              @endif
            </div>
        </div>
    </nav>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-location-dot me-1"></i>Ubicación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @livewire('counter')
      </div>
    </div>
  </div>
  <!-- Modal -->
<div class="modal fade" id="exampleModal25" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Suscripciones</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <ul class="list-group">
                @foreach ($subscribeds as $key)
                    <a href="/tienda/{{ str_replace(' ', '-', $key->store->name) }}">
                        <li class="list-group-item d-flex" style="justify-content: start;align-items: center;">
                            <img src="{{ asset($key->store->image) }}" alt="img" style="width: 3rem;
                            height: 3rem;
                            border-radius: 100%;
                            margin-right: 1rem;
                            border: solid 1px #dee2e6;
                            object-fit: cover;">{{ $key->store->name }}
                        </li> 
                    </a>               
                @endforeach
            </ul>
        </div>
      </div>
    </div>
  </div>

    <!-- Modal -->
<div class="modal fade" id="exampleModal26" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tiendas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @livewire('search-store')
      </div>
    </div>
  </div>
  <div class="modal fade" id="exampleModal27" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Talleres</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @livewire('search-taller')
      </div>
    </div>
  </div>
</div>

