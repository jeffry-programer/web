<x-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <img class="img-fluid" src=" {{ asset('images/tulobuscas.png') }} " style="cursor: pointer;margin-bottom:2rem" onclick="window.location.replace('/');">


        <div class="mb-4 text-sm text-gray-600">
            {{ __('¿Has olvidado tu contraseña? No te preocupes. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña, permitiéndote elegir una nueva.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                Le hemos enviado por correo electrónico el enlace para restablecer su contraseña.            
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        @if (!session('status'))
            <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
                @csrf

                <div class="block">
                    <x-label for="email" value="{{ __('Correo') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" placeholder="Ingrese tu correo para recuperar la cuenta" name="email" :value="old('email')" required autofocus/>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <x-button>
                        {{ __('Enviar enlace de recuperación a mi correo electrónico') }}
                    </x-button>
                </div>
            </form>
        @endif
    </x-authentication-card>
</x-app-layout>
