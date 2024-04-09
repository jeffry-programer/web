<div>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner"
            style="background-image: url('{{ asset('images/1.jpg') }}');background-size: 100% 100%;background-repeat: no-repeat;">
            <div class="carousel-item active">
                <img src="{{ asset('images/2.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/3.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/4.png') }}" class="d-block w-50 img-carrusel"
                    style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="row py-2" style="height: 5.5rem;">
        <div class="slide-option">
            <div id="infinite" class="highway-slider">
                <div class="container highway-barrier"
                    style="padding-bottom: 0rem;width: 100%;max-width: 99%;height: 4rem;">
                    <ul class="highway-lane">
                        @for ($i = 0; $i < 3; $i++)
                            @foreach ($publicities as $key)
                                <li class="highway-car">
                                    <div class="card">
                                        <div class="card-body" style="padding: 0rem;">
                                            <div class="contenedor-imagen"
                                                onclick="goPagePublicity({{ $key->id }})">
                                                <img src="{{ asset($key->image) }}" class="img-fluid imagen-zoom"
                                                    alt="Imagen 1">
                                                <div class="texto-encima">{{ $key->title }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-secondary mx-3" role="alert">
        <h6 style="font-size: 1.2rem;margin-top: 0.5rem;">Tiendas con promociones</h6>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($stores as $store)
                <div class="col-12 col-md-4 mt-3">
                    <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}">
                        <div class="card card-store">
                            <div class="zoom-container">
                                <img class="zoomed-image" src="{{ asset($store->image) }}"
                                    alt="Descripción de la imagen">
                            </div>
                            <div class="card-body" style="padding-bottom: 4rem;">
                                <h5 class="card-title">{{ $store->name }}</h5>
                                <p class="card-text">{{ $store->description }}</p>
                                <p class="position-absolute bottom-0 start-0" style="padding: 1rem;width: 18rem;"><i
                                        class="fa-solid fa-location-dot me-1"></i>{{ $store->city->name }} -
                                    {{ $store->address }}</p>
                                <a href="/tienda/{{ str_replace(' ', '-', $store->name) }}"
                                    class="btn btn-warning position-absolute bottom-0 end-0"
                                    style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @if (session('welcome_modal_shown'))
        <!-- Modal de bienvenida -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="welcomeModalLabel">Estimad@ {{ Auth::user()->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Es un placer darte la Bienvenida a Tulobuscas. Nos complace informarte que tu empresa ha completado con éxito todo el proceso de registro en nuestro sistema.</p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">A partir de ahora, comenzarás a disfrutar de los numerosos beneficios que Tulobuscas te ofrece. Nuestro objetivo es proporcionarte las herramientas y recursos necesarios para impulsar tu empresa.</p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Además, nos complace ofrecerte 30 días de servicio gratuito como parte de nuestro recibimiento. Tendrás acceso completo a todas nuestras características y servicios. Esperamos que explores y descubras todo lo que Tulobuscas tiene para ofrecer.</p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Si tienes alguna pregunta o necesitas asistencia no dudes en ponerte en contacto con nosotros. Estamos aquí para ayudarte.</p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Reiteramos nuestra calurosa bienvenida a Tulobuscas. Estamos entusiasmados y esperamos con interés la oportunidad de colaborarte y crecer juntos.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para mostrar automáticamente el modal -->
        <script>
            $(document).ready(function() {
                $('#welcomeModal').modal('show');
            });
        </script>

        <?php
            // Eliminar la variable de sesión 'welcome_modal_shown'
            Illuminate\Support\Facades\Session::forget('welcome_modal_shown');
        ?>
    @endif
</div>
