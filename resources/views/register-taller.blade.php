<x-app-layout>
    <div class="container" style="max-width: 100%;
    padding: 3rem !important;
    background-image: url('{{asset('images/para-taller.jpg')}}');
    background-repeat: no-repeat;
    background-size: 85rem;
    background-size: cover;
    background-position: center;">
        <div class="row">
            <div class="col-md-8 mt-md-5 pb-md-5 text-white">
                <h1 class="mb-4" style="text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Bienvenido a <b style="color: #6495ed;font-size: 2.0rem;">Tulobuscas</b>, puedes dar a conocer el servicio que brindas en tu taller</h1>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Aquí, nos esforzamos para darte la mejor experiencia posible.
                    ¿Sabías que al registrarte en nuestro sitio web puedes disfrutar de mucho más?</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">No pagas por pertenecer a la red de <b style="color: #6495ed;font-size: 1.4rem;">Tulobuscas</b> los primeros 30 días</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Los usuarios podrán comunicarse directamente contigo y acceder a los servicios de tu Taller.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Tendrás un link de página web y los clientes podrán ingresar desde cualquier dispositivo.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Tu seguridad es nuestra prioridad. Al registrarte, te garantizamos una experiencia segura y protegida.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Si tienes alguna pregunta o problema, nuestro equipo de soporte al cliente estará listo para ayudarte.</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Es importante que te registres para poder ofrecerte este servicio</p>
            </div>
            <div class="col-md-4">
                <div class="card my-3" style="border: solid 1px #aaa !important;
                border-radius: 15px !important;">
                    <div class="card-header text-center" style="border: none;
                    background: white;
                    padding: 1rem;
                    border-radius: 15px;">
                        <h3 class="fw-bolder">Registrate como un taller</h3>
                    </div>
                    <div class="card-body">
                        <x-validation-errors class="mb-4" />
        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
            
                            <div>
                                <x-label for="name" value="{{ __('Nombre') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="email" value="{{ __('Correo') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="password" value="{{ __('Contraseña') }}" />
                                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
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
        <h1 class="py-3">¿Cuáles son los beneficios de registrar tu Grúa en tulobuscas?</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card" style="background: transparent !important;
                border: solid 2px white !important;
                padding: 1rem;
                margin-bottom: 2rem;
                color: white;
                text-align: left;height: 16rem;">
                    <h6 style="font-size: 1.5rem;
                    font-weight: bolder;">Aumenta el trafico de clientes a tu servicio
        desde tulobuscas</h6>
                    <p> Logra aunmentar el número de clientes gracias a la gran red
                        de usuarios de tulobuscas</p>
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
                    font-weight: bolder;">Aumenta la exposición de tu servicio con tulobuscas</h6>
                    <p>Potencia la visibilidad de tu servicio de grúa para atraer a más clientes.
                        Con nuestro sistema ‘TuLoBuscas’, puedes asistir a personas que se encuentran
                        en situaciones de emergencia con sus vehículos accidentados. ¡Únete a nosotros y marca la diferencia!</p>
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
                    font-weight: bolder;"> Obtendras información extra</h6>
                    <p>  O Obtendrás información valiosa que potenciará tus servicios. Por ejemplo, te proporcionaremos datos sobre
                        las zonas con mayor índice de accidentes en tu ciudad de registro. Esta información te permitirá optimizar
                        tus operaciones y brindar un servicio más eficiente..</p>
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
                    font-weight: bolder;">Tendras un código QR</h6>
                    <p>   Mediante el uso de un código QR físico, ubicado en las puertas del camión,
                        los clientes potenciales pueden consultar fácilmente los horarios y servicios de la grúa registrada.
                        Esta es una forma eficiente y moderna de proporcionar información esencial a tus clientes.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>