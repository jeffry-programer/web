<x-app-layout>
    <div class="container" style="max-width: 100%;
    padding: 3rem !important;
    background-image: url('{{asset('images/para-usuario.jpg')}}');
    background-repeat: no-repeat;
    background-size: 85rem;
    background-size: cover;
    background-position: center;">
        <div class="row">
            <div class="col-md-8 mt-md-5 pb-md-5 text-white">
                <h1 class="mb-4" style="text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Ahorra tiempo y dinero</h1>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">¿Sabías que al registrarte en <b style="color: #6495ed;font-size: 1.4rem;">Tulobuscas</b> puedes encontrar el repuesto, lubricante o accesorio que estás buscando 
                    más rápido y con la ubicación exacta de la Tienda que lo tiene disponible.?</p>
                    <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Aquí puedes comunicarte directamente con un taller o grúa que necesites en un momento de urgencia.</p>
                    <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Es gratis pertenecer a la red  y por tiempo ilimitado.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Al registrarte, obtienes acceso completo a todos nuestros servicios. Sin una cuenta, solo puedes acceder a una pequeña parte de lo que ofrecemos.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Podemos personalizar tu experiencia en función a tus preferencias y necesidades.</p>
            </div>
            <div class="col-md-4">
                <div class="card my-3" style="border: solid 1px #aaa !important;
                border-radius: 15px !important;">
                    <div class="card-header text-center" style="border: none;
                    background: white;
                    padding: 1rem;
                    border-radius: 15px;">
                        <h3 class="fw-bolder">Registrate como un usuario</h3>
                    </div>
                    <div class="card-body">
                        <x-validation-errors class="mb-4" />
        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <input type="hidden" name="profiles_id" value="3">
            
                            <div>
                                <x-label for="name" value="{{ __('Nombre') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="email" value="{{ __('Correo') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autocomplete="username" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="password" value="{{ __('Contraseña') }}" />
                                <div class="relative">
                                    <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="new-password" />
                                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5" style="position: absolute;right: .8rem;top: .8rem;">
                                        <svg id="eye-icon" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
            
                            <div class="mt-4">
                                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            </div>
            
                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mt-4">
                                    <x-label for="terms">
                                        <div class="flex items-center">
                                            <x-checkbox name="terms" id="terms" required />
            
                                            <div class="ms-2">
                                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </x-label>
                                </div>
                            @endif
            
                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                    {{ __('¿Ya estas registrado?') }}
                                </a>
            
                                <x-button class="ms-4">
                                    {{ __('Registro') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center" style="background: #6495ed;
    color: white;
    padding: 2rem;">
        <h1 class="py-3">¿Cuáles son los beneficios de registrarte como cliente?</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card" style="background: transparent !important;
                border: solid 2px white !important;
                padding: 1rem;
                margin-bottom: 2rem;
                color: white;
                text-align: left;height: 16rem;">
                    <h6 style="font-size: 1.5rem;
                    font-weight: bolder;">Ahorra tiempo</h6>
                    <p> Al consultar el repuesto que necesita, el sistema muestra que tiendas
                        tienen lo que buscas, facilitando informacion de la tienda
                        como datos de contacto.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: transparent !important;
                border: solid 2px white !important;
                padding: 1rem;
                margin-bottom: 2rem;
                color: white;
                text-align: left;height: 16rem;">
                    <h6 style="font-size: 1.5rem;
                    font-weight: bolder;">Ahorra dinero</h6>
                    <p> Ahorrarás dinero, al no tener que recorrer toda la ciudad averiguando
                        quien tiene el repuesto que necesitas, podrás ir directamente a la tienda
                        que tiene ese repuesto, así se ahorra gasolina o dinero dedicado al desplazamiento.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: transparent !important;
                border: solid 2px white !important;
                padding: 1rem;
                margin-bottom: 2rem;
                color: white;
                text-align: left;height: 16rem;">
                    <h6 style="font-size: 1.5rem;
                    font-weight: bolder;"> Aprovecho ofertas</h6>
                    <p>  Aprovecharas las diferentes promociones que las tiendas tienen para ti, al suscribirte
                        a las diferentes tiendas podras recibir notificaciones cuando ellas registren sus promociones.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: transparent !important;
                border: solid 2px white !important;
                padding: 1rem;
                margin-bottom: 2rem;
                color: white;
                text-align: left;height: 16rem;">
                    <h6 style="font-size: 1.5rem;
                    font-weight: bolder;">Te recomendamos talleres</h6>
                    <p>  El sistema te brinda un apartado donde podras consultar los diferentes talleres mecanicos
                        que se encuentran registrados en tulobuscas, el taller que mejor se adapta a tí.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
