<x-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <img class="img-fluid" src=" {{ asset('images/tulobuscas.png') }} " style="cursor: pointer;margin-bottom:2rem" onclick="window.location.replace('/');">

        <div class="mb-4 text-sm text-gray-600" style="text-align: justify;">
            {{ __('Antes de continuar, ingresa por favor a tu bandeja de correo electrónico y da clic en el enlace que te enviamos, si no recibiste el correo electrónico, con gusto te enviaremos otro.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('Se envió un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó en la configuración de su perfil.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Reenviar correo electrónico de verificación') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-app-layout>