<style>
     body {
        background: #f3f4f6 !important;
    }

    .mb-8 {
        margin-bottom: 8rem;
    }

    .justify-div {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<x-app-layout>
    <div class="mb-8">
        <x-authentication-card>
            <x-slot name="logo">
            </x-slot>
    
            <div style="display: flex;justify-content: center;align-items: center;">
                <img class="img-fluid" src=" {{ asset('images/piePagiina.png') }} " style="width : 60%;cursor: pointer;margin-bottom:2rem"
                onclick="window.location.replace('/');">
            </div>
    
    
    
            <x-validation-errors class="mb-4" />
    
            <form method="POST" action="{{ route('password-update') }}">
                @csrf
    
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
    
                <div class="block">
                    <x-label for="email" value="{{ __('Correo') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)"
                        required autofocus autocomplete="username" />
                </div>
    
                <div class="mt-4 relative">
                    <x-label for="password" value="{{ __('Contraseña') }}" />
                    <x-input id="password" placeholder="Por favor ingresa tu contraseña" class="block mt-1 w-full"
                        type="password" name="password" required autocomplete="current-password" />
    
                    <!-- Ícono para ver/ocultar contraseña -->
                    <span onclick="togglePasswordVisibility()"
                        style="cursor: pointer; position: absolute; top: 70%; right: 10px; transform: translateY(-50%);">
                        <svg id="toggleIcon" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4 relative">
                    <x-label for="password_confirmation" value="{{ __('Contraseña') }}" />
                    <x-input id="password_confirmation" placeholder="Por favor ingresa tu contraseña" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required autocomplete="password_confirmation" />
    
                    <!-- Ícono para ver/ocultar contraseña -->
                    <span onclick="togglePasswordVisibility2()"
                        style="cursor: pointer; position: absolute; top: 70%; right: 10px; transform: translateY(-50%);">
                        <svg id="toggleIcon2" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                        </svg>
                    </span>
                </div>
    
                <div class="flex items-center justify-end mt-4">
                    <x-button>
                        {{ __('Resetear contraseña') }}
                    </x-button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-app-layout>

<script>
    function togglePasswordVisibility2() {
        const passwordInput = document.getElementById('password_confirmation');
        const toggleIcon = document.getElementById('toggleIcon2');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }

    function togglePasswordVisibility(){
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML =
                '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
        }
    }
</script>
