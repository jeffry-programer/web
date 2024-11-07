<style>
    #div-register{
        display: block;
        text-align: center;
    }

    .custom-checkbox {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        --tw-text-opacity: 1;
        font-weight: 500;
        font-size: .875rem;
        line-height: 1.25rem;
    }

    /* Oculta el checkbox original */
    .custom-checkbox input[type="checkbox"] {
        display: none;
    }

    /* Cuadro personalizado del checkbox */
    .custom-checkbox .checkbox-box {
        width: 18px;
        height: 18px;
        background-color: #e0e0e0;
        border-radius: 4px;
        margin-right: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        border: 2px solid transparent;
    }

    /* Estilo cuando está seleccionado */
    .custom-checkbox input[type="checkbox"]:checked+.checkbox-box {
        background-color: cornflowerblue;
        box-shadow: 0px 0px 5px cornflowerblue;
    }

    /* Ícono de checkmark */
    .custom-checkbox .checkbox-box::after {
        content: "";
        display: none;
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Muestra el checkmark cuando está seleccionado */
    .custom-checkbox input[type="checkbox"]:checked+.checkbox-box::after {
        display: block;
    }
</style>

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
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">¿Sabías que al registrarte en nuestro sitio web puedes disfrutar de mucho más?</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">No pagas por pertenecer a la red de <b style="color: #6495ed;font-size: 1.4rem;">Tulobuscas</b> los primeros 30 días</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Los usuarios podrán comunicarse directamente contigo y acceder a los servicios de tu Taller</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">En nuestro espacio tendrás tu perfil y los clientes podrán visitarlo desde cualquier dispositivo</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Al registrarte, te garantizamos una experiencia segura y protegida</p>
                <p style="font-size: 1.2rem;font-weight: 900;text-shadow: 1px 1px 1px #000, -1px 1px 1px #000, -1px -1px 0 #000, 1px -1px 0 #000">Si tienes alguna pregunta o problema, nuestro equipo de soporte al cliente estará listo para ayudarte</p>
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
        
                        <form method="POST" action="{{ route('register2') }}">
                            @csrf

                            <input type="hidden" name="profiles_id" value="4">
            
                            <div>
                                <x-label for="name" value="{{ __('Nombre del titular o responsable') }}" />
                                <x-input id="name" placeholder="Por favor ingresa tu nombre" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="email" value="{{ __('Correo del titular o responsable') }}" />
                                <x-input id="email"  placeholder="Por favor ingresa tu correo" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autocomplete="username" />
                            </div>
            
                            <div class="mt-4">
                                <x-label for="password" value="{{ __('Contraseña') }}" />
                                <div class="relative">
                                    <x-input id="password" class="block mt-1 w-full pr-10" type="password" placeholder="Por favor ingresa una contraseña"
                                        name="password" required autocomplete="new-password" />
                                    <button type="button" onclick="togglePasswordVisibility()"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                        style="position: absolute;right: .8rem;top: .8rem;">
                                        <svg id="eye-icon" class="h-5 w-5 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                                <div class="relative">
                                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" placeholder="Por favor repite la contraseña"
                                        name="password_confirmation" required autocomplete="new-password" />
                                    <button type="button" onclick="togglePasswordVisibility2()"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                        style="position: absolute;right: .8rem;top: .8rem;">
                                        <svg id="eye-icon2" class="h-5 w-5 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-label for="password_confirmation" value="{{ __('Fecha de Nacimiento') }}" />
                                <div class="relative">
                                    <x-input id="birthdate" class="block mt-1 w-full" type="date"
                                        name="birthdate" required autocomplete="birthdate" />
                                </div>
                            </div>
            
                            <div class="mt-4">
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <label class="custom-checkbox">
                                            <input type="checkbox" name="terms" id="terms" required onchange="toggleRegisterButton()"/>
                                            <span class="checkbox-box"></span>
                                            <a href="/terminos" target="_blank" style="color: cornflowerblue !important;">Acepto los terminos, condiciones y las
                                                políticas de privacidad</a>
                                        </label>
                                    </div>
                                </x-label>
                            </div>
            
                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                    {{ __('¿Ya estas registrado?') }}
                                </a>
            
                                <x-button class="ms-4" id="register-button" disabled>
                                    {{ __('Registrarme') }}
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
                text-align: left;height: auto;">
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
                text-align: left;height: auto;">
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
                text-align: left;height: auto;">
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
                text-align: left;height: auto;">
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


<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }

    function togglePasswordVisibility2() {
        var passwordInput = document.getElementById('password_confirmation');
        var eyeIcon = document.getElementById('eye-icon2');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }

    function toggleRegisterButton() {
        var checkbox = document.getElementById('terms');
        var button = document.getElementById('register-button');
        button.disabled = !checkbox.checked;
    }
</script>
