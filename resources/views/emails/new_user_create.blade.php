@component('mail::message')
# ¡Bienvenido a nuestra aplicación!

Hola {{ $user->name }},

Te damos la bienvenida a nuestra aplicación. Tu cuenta ha sido creada correctamente.

Gracias por unirte a nosotros.

Gracias,<br>
{{ config('app.name') }}
@endcomponent