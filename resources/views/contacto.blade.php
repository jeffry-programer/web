<x-app-layout>

  <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-md-block">
    <div class="container">
      <style>
        .active>a {
          background-color: #007bff !important;
          color: #ffffff !important;
        }
      </style>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto gap-6">
          <li class="nav-item ">
            <a class="nav-link" href="{{asset('terminos')}}">Términos y condiciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{asset('preguntas')}}">Preguntas frecuentes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{asset('politicas')}}">Politica de privacidad</a>
          </li>
          <li class="nav-item  active">
            <a class="nav-link" href="{{asset('contacto')}}">Contacto</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{asset('ayuda')}}">Ayuda</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container" style="max-width: 100%;padding: 2rem;">
    <div class="row">
      <div class="col-12 col-md-6">
        <h2 class="mt-5">Contacta con el área comercial</h2>
        <p class="mt-5">Para cualquier duda o consulta, el usuario puede contactar al soporte a través
          del correo electrónico atencionalcliente@tulobuscas.app o escribiendo al Whattsap +58 412-965-6872</p>
      </div>
      <div class="col-12 col-md-6">
        <img src="{{ asset('images/celular.png') }}" class="img-fluid">
      </div>
    </div>
  
    <div class="row">
      <div class="col-12 col-md-6">
        <img src="{{ asset('images/trabajador-sin fondo.png') }}" class="img-fluid">
      </div>
      <div class="col-12 col-md-6">
        <h2 class="mt-5">Contacta con el área técnica</h2>
        <p class="mt-5">Si tienes alguna pregunta sobre el sistema o encuentras dificultades de navegación, no dudes
          en comunicarte al correo soporte@tulobuscas.app</p>
      </div>
  
    </div>
  
  
    <div class="row">
      <div class="col-md-12">
        <div class="p-4 d-flex justify-content-center">
          <h2 class="text-justify mt-5">Comunícate con nuestro equipo comercial a través de los siguientes números de
            teléfono locales.</h2>
        </div>
        <div class="p-4 d-block justify-content-center text-center">
          <h3 class="mt-3">Venezuela</h3>
          <p>+58 412-965-6872</p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>