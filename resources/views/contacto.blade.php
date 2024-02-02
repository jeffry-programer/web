<x-app-layout>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
          <style>
            .active > a {
              background-color: #007bff !important;
              color: #ffffff !important;
            }
          </style>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto gap-6">
              <li class="nav-item ">
                <a class="nav-link" href="http://127.0.0.1:8000/terminos">Términos y condiciones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="http://127.0.0.1:8000/preguntas">Preguntas frecuentes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="http://127.0.0.1:8000/politicas">Politica de privacidad</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="http://127.0.0.1:8000/contacto">Contacto</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="http://127.0.0.1:8000/ayuda">Ayuda</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    

      <div class="row">
          <div class="col-12 col-md-6">
              <h2 class="ms-5 mt-5">Contacta con el área comercial</h2>
              <p class="ms-5 mt-5">Únete a la red de Tulobuscas y aprovecha todas las ventajas! Regístrate en nuestra página principal o contáctanos llamando al siguiente número: 0424-55555-555.</p>
          </div>
          <div class="col-12 col-md-6">
              <img src="{{ asset('images/celular.png') }}" class="img-fluid" alt="">
          </div>
      </div>

    <div class="row">
          <div class="col-12 col-md-6">
            <img src="{{ asset('images/trabajador-sin fondo.png') }}" class="img-fluid" alt="">
        </div>
          <div class="col-12 col-md-6">
              <h2 class="ms-5 mt-5">Contacta con el área técnica</h2>
              <p class="ms-5 mt-5">Si tienes alguna pregunta sobre el sistema o encuentras dificultades de navegación, no dudes en comunicarte con nuestra línea de ayuda al 569781223.</p>
          </div>

     </div>


      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="p-4" style="margin-left: 100px;">
                    <h2 class="text-justify mt-5">Comunícate con nuestro equipo comercial a través de los siguientes números de teléfono locales.</h2>            
                </div> 
            </div>
        </div>
      </div>
    
      <div class="row justify-content-center">
          <div class="col-12 col-md-6">
            <h3 class="ms-5 mt-5">Venezuela</h3> 
            <p class="ms-5 mt-5">+58 414 9875 25</p> 
          </div>
            <div class="col-12 col-md-6">
              <h3 class="ms-5 mt-5">Colombia</h3>
              <p class="ms-5 mt-5">+57 556 565 65</p>
          </div>        
      </div>

  
    


</x-app-layout>