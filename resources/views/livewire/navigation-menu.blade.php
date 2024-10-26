<style>
  /*----------------------------------------------------------------------------------*/
  #myInput98 {
    border: none;
  }

  #myUL98 {
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

  #myUL98 li {
    cursor: pointer;
  }

  #myUL98 li a {
    padding: 10px;
    display: block;
    text-decoration: none;
    color: #000;
  }

  #myUL98 li a:hover {
    background-color: #f4f4f4;
  }

  @media (max-width: 768px) {
    .lupitaaa {
      margin-left: 1rem;
    }
  }

  .card-title {
    width: 100%;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
  }


  .card-text {
    width: 100%;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
  }
</style>

<div>
  @php
  if(isset(Auth::user()->id)){
  if(Auth::user()->email_verified_at == "" && $_SERVER['REQUEST_URI'] != '/email/verify'){
  echo "<script>
    window.location.replace('/email/verify');
  </script>";
  }
  if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 2 && !Auth::user()->store){
  if($_SERVER['REQUEST_URI'] != '/register-data-store'){
  echo "<script>
    window.location.replace('/register-data-store');
  </script>";
  }
  }
  if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 4 && !Auth::user()->store){
  if($_SERVER['REQUEST_URI'] != '/register-data-taller'){
  echo "<script>
    window.location.replace('/register-data-taller');
  </script>";
  }
  }
  if(Auth::user()->email_verified_at != "" && Auth::user()->profiles_id == 5 && !Auth::user()->store){
  if($_SERVER['REQUEST_URI'] != '/register-data-grua'){
  echo "<script>
    window.location.replace('/register-data-grua');
  </script>";
  }
  }
  }
  @endphp
  <nav x-data="{ open: false }" class="bg-white border-b border-gray-100"
    style="height: 6rem;height: 6rem;border: solid 0px;border-bottom: solid 1.4rem #6495ED;padding-bottom: 7rem;margin-bottom: 0rem;">
    <div class="row pt-3" style="width: 100%;">
      <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
          aria-controls="offcanvasExample" style="font-size: 1.2rem;
                border: solid 1px #dfdfdf;">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasExampleLabel" style="font-size: 1.5rem;">Tulobuscas</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="block px-4 py-2 text-xs text-gray-400" style="font-size: .9rem;">
          {{ __('Otras opciones') }}
        </div>

        <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal25" style="text-decoration: none"
          style="font-size: 1rem;">
          <i class="fa-solid fa-user me-1"></i>{{ __('Suscripciones') }}
        </x-dropdown-link>

        <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal26" style="text-decoration: none"
          style="font-size: 1rem;">
          <i class="fa-solid fa-circle-user me-1"></i>{{ __('Tiendas') }}
        </x-dropdown-link>


        <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal27" style="text-decoration: none"
          style="font-size: 1rem;">
          <i class="fa-solid fa-house me-1"></i>{{ __('Talleres') }}
        </x-dropdown-link>

        <x-dropdown-link href="#" data-bs-toggle="modal" data-bs-target="#exampleModal28" style="text-decoration: none"
          style="font-size: 1rem;">
          <i class="fa-solid fa-truck-fast me-1"></i>{{ __('Grúas') }}
        </x-dropdown-link>
      </div>
      <div class="col-10 col-md-3 d-flex align-items-center justify-content-center">
        <img class="img-fluid" style="height: 90%" src=" {{ asset('images/tulobuscas.png') }} " alt="img" style="cursor: pointer;"
          onclick="window.location.replace('/');">
      </div>
      <div class="col-2 d-flex d-md-none align-items-center justify-content-center">
        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
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
                <a class="nav-link active" aria-current="page" href="{{ route('profile.show') }}"><i
                    class="fa-solid fa-user-plus me-2"></i>Perfil</a>
              </li>

              @if(Auth::user()->profiles_id == 1)
              <li class="nav-item">
                <a class="nav-link active" href="/admin/table-management/Cajas"><i
                    class="fa-solid fa-circle-user me-2"></i>{{ __('Administración') }}</a>
              </li>
              @endif

              @if(Auth::user()->store)
              <li class="nav-item">
                <a class="nav-link active" href="/tienda/{{ str_replace(' ','-', $link_store) }}"><i
                    class="fa-solid fa-house me-1"></i>Mi {{ strtolower(Auth::user()->store->typeStore->description)
                  }}</a>
              </li>
              @endif

              <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" style="margin-top: 0rem !important">
                  @csrf
                  <button class="nav-link active" aria-current="page"><i
                      class="fa-solid fa-right-to-bracket me-2"></i>Cerrar sesión</button>
                </form>
              </li>
              @else
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('registro') }}"><i
                    class="fa-solid fa-user-plus me-2"></i>Registro</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('login') }}"><i
                    class="fa-solid fa-right-to-bracket me-2"></i>Iniciar sesión</a>
              </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <div class="col-12 d-flex col-md-5 align-items-center justify-content-center">
        <form action="{{ route('search-stores') }}" class="row" id="form-search" autocomplete="off">
          <input type="hidden" id="value-state" name="iSVBGR6m3mmQdQRQCa">
          <input type="hidden" id="value-municipality" name="DPLY40rNOyz0hl">
          <input type="hidden" id="value-sector" name="I9CLmGfm0ppURDM">
          <div class="col-2 col-md-3 d-flex align-items-center justify-content-center">
            <button type="button" style="display: flex;" data-bs-toggle="modal" data-bs-target="#exampleModal"
              class="select-search"><i class="fa-solid fa-location-dot me-3 my-auto"></i><span class="d-none d-md-flex"
                id="btn-ubi">Ubicacion</span></button>
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
            <div class="autocomplete">
              <input class="input-search" name="product" id="myInput98" placeholder="Busca el repuesto" type="text">
              <ul id="myUL98" style="overflow-y: hidden;">
              </ul>
            </div>
          </div>
          <div class="col-1 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-magnifying-glass icons-search pointer lupitaaa" onclick="searchData()"></i>
            <i class="fa-solid fa-microphone icons-search pointer" style="margin-left: -1rem;"></i>
          </div>
        </form>
      </div>
      <div class="d-none d-md-flex col-md-3 align-items-center justify-content-center">
        @if(Auth::user())
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <?php
                              if(Auth::user()->image == null){
                                $letter = strtoupper(Auth::user()->name[0]);
                                $ruta_imagen = "https://ui-avatars.com/api/?name=".$letter."&amp;color=7F9CF5&amp;background=EBF4FF";
                              }else{
                                $assets = asset('');
                                $ruta_imagen = Auth::user()->image;
                                if(!str_contains($ruta_imagen, 'storage')) $ruta_imagen = '/storage/'.$ruta_imagen;
                                if(str_contains($ruta_imagen, 'http://localhost/')) str_replace('http://localhost/', $assets, $ruta_imagen);
                              }
                          ?>
            <button
              class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
              <img class="h-8 w-8 rounded-full object-cover" style="width: 3rem;
                                height: 3rem;" src="{{ asset($ruta_imagen) }}" alt="{{ Auth::user()->name }}" />
            </button>
          </x-slot>

          <x-slot name="content">
            <div class="block px-4 py-2 text-xs text-gray-400">
              {{ __('Manejar cuenta') }}
            </div>

            <x-dropdown-link href="{{ route('profile.show') }}" style="text-decoration: none">
              <i class="fa-solid fa-user me-1"></i>{{ __('Perfil') }}
            </x-dropdown-link>

            @if(Auth::user()->profiles_id == 1)
            <x-dropdown-link href="/admin/table-management/Cajas" style="text-decoration: none">
              <i class="fa-solid fa-circle-user me-1"></i>{{ __('Administración') }}
            </x-dropdown-link>
            @endif

            @if(Auth::user()->store)
            <x-dropdown-link href="/tienda/{{ str_replace(' ','-', $link_store) }}" style="text-decoration: none">
              <i class="fa-solid fa-house me-1"></i>Mi {{ strtolower(Auth::user()->store->typeStore->description) }}
            </x-dropdown-link>
            @endif

            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
            <x-dropdown-link href="{{ route('api-tokens.index') }}">
              {{ __('API Tokens') }}
            </x-dropdown-link>
            @endif

            <div class="border-t border-gray-200"></div>
            <form method="POST" action="{{ route('logout') }}" x-data>
              @csrf
              <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                style="text-decoration: none">
                <i class="fa-solid fa-right-from-bracket me-1"></i>{{ __('Cerrar sesion') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
        @else
        @if (Route::has('login'))
        <div class="row">
          <div class="col-12 col-lg-6">
            <a href="{{ route('login') }}"
              class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
              style="text-decoration: none"><i class="fa-solid fa-right-to-bracket me-1"></i>Iniciar sesión</a>
          </div>
          <div class="col-12 mt-2 col-lg-6 mt-lg-0">
            <a href="{{ route('registro') }}"
              class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
              style="text-decoration: none"><i class="fa-solid fa-user-plus me-1"></i>Registro</a>
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
            <a href="/tienda/{{ str_replace(' ', '-', $key->store->name) }}" target="_blank">
              <li class="list-group-item d-flex" style="justify-content: start;align-items: center;border: none;">
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
        @livewire('search-store')
      </div>
    </div>
  </div>
  <div class="modal fade" id="exampleModal27" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        @livewire('search-taller')
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal28" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        @livewire('search-grua')
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
<script>
  $(document).ready(function() {
            $('#myInput98').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    $.ajax({
                        url: "{{ route('autocomplete-products') }}",
                        method: "GET",
                        data: {
                            term: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#myUL98').empty();
                            $.each(data, function(key, value) {
                              $('#myUL98').append('<li class="list-item" data-name="'+value.name+'"><a>'+value.name+'</a></li>');
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.list-item', function(){
            var name = $(this).data('name');
            $('#myInput98').val(name);
            $('#myUL98').empty();
        });

        $(document).on('click', function(e){
            if(!$(e.target).closest('#myUL98').length && !$(e.target).is('#myUL98')) {
                $('#myUL98').empty();
            }
        });

        function selectCity(id) {
            $("#city-search").val(id);
        }

        //Guardar ubicación
        $("#btn-save-ubi").click(() => {
            var nameMunicipality = $(`#municipality-${$("#municipality").val()}`).text();
            var sectorId = $("#sector").val();
            var municipalityId = $("#municipality").val();
            var stateId = $("#state").val();

            localStorage.setItem("name_municipality", nameMunicipality);
            localStorage.setItem("id_sector", sectorId);
            localStorage.setItem("id_municipality", municipalityId);
            localStorage.setItem("id_states", stateId);

            $("#value-state").val(stateId);
            $("#value-municipality").val(municipalityId);
            $("#value-sector").val(sectorId);

            $("#btn-ubi").html(`${nameMunicipality}`);
            $("#exampleModal").modal('hide');
        });

        $(document).ready(() => {
            $("#state").change(() => {
              $.ajax({
                  url: '/states/' + $("#state").val() + '/municipalities',
                  type: 'GET',
                  success: function(data) {
                      var options = '<option value="">Selecciona un municipio</option>';
                      data.forEach((key) => {
                          options += `<option value="${key.id}" data-name="${key.name}" id="municipality-${key.id}">${key.name}</option>`;
                      });
                      $('#municipality').html(options);
                  }
              });
            });
            var nameMunicipality = localStorage.getItem('name_municipality');
            if (nameMunicipality !== null) {
                var sectorId = localStorage.getItem('id_sector');
                var municipalityId = localStorage.getItem('id_municipality');
                var stateId = localStorage.getItem('id_states');

                $("#btn-ubi").html(`${nameMunicipality}`);
                $.ajax({
                    url: `/update-counter-component?stateId=${stateId}&municipalityId=${municipalityId}&sectorId=${sectorId}`,
                    type: 'GET',
                    success: function(data){
                        var plantilla = '<option>Seleccione una opción</option>';
                        data.states.forEach(element => {
                            plantilla += `<option value="${element.id}">${element.name}</option>`;
                        });
                        $("#state").html(plantilla);
                        $("#state").show();
                        $("#state").val(stateId);
                        var plantilla = '<option>Seleccione una opción</option>';
                        data.municipalities.forEach(element => {
                            plantilla += `<option value="${element.id}" id="municipality-${element.id}" data-name="${element.name}">${element.name}</option>`;
                        });
                        $("#municipality").html(plantilla);
                        $("#municipality").show();
                        $("#municipality").val(municipalityId);
                        var plantilla = '<option value="Todos">Todos</option>';
                        data.sectors.forEach(element => {
                            plantilla += `<option value="${element.id}">${element.description}</option>`;
                        });
                        $("#sector").html(plantilla);
                        $("#sector").show();
                        $("#sector").val(sectorId);
                    }
                });

                console.log(stateId);

                $("#value-state").val(stateId);
                $("#value-municipality").val(municipalityId);
                $("#value-sector").val(sectorId);
            }
            var category = localStorage.getItem('categories_id');
            if (category !== null) {
                $("#select-search-categories").val(category);
            }
        });
</script>