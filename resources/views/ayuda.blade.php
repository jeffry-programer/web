<x-app-layout>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-md-block">
        <div class="container">
          <style>
            .active > a {
              background-color: #007bff !important;
              color: #ffffff !important;
            }

                  /* Estilo adicional para centrar horizontalmente */
                  .center-block {
                  display: block;
                  margin-left: auto;
                  margin-right: auto;
                }

                .texto-azul {
                  color: #0049ac;
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
              <li class="nav-item">
                <a class="nav-link" href="{{asset('contacto')}}">Contacto</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="{{asset('ayuda')}}">Ayuda</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    
      <div class="container justify-content-center">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="p-4">
                        <h2 class="text-justify mt-5">¿Como buscar repuestos en  <span class="texto-azul">Tulo</span>buscas? Ahorra tiempo y dinero </h2>            
                        <p class="text-justify mt-10">Descubre en solo 3 pasos las tiendas que ofrecen los repuestos que necesitas<br><br>
                                                    <li> Localiza el repuesto que necesitas a través de nuestro buscador principal.</li><br>
                                                    <li> Aquí encontrarás un listado de tiendas que tienen el repuesto que estás buscando, puedes elegir la tienda que desees</li><br>
                                                    <li> Comunícate con la tienda y consulta lo que necesites</li></p>               

                        <div class="container">
                            <div class="row justify-content-center">
                            <div class="col-md-8 text-center">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item center-bloc" src="https://www.youtube.com/embed/PeEqb8jDBls"></iframe>
                                </div>
                            </div>
                            </div>
                       </div>

                       <h2 class="text-justify mt-5">¿Porque usar la plataforma  <span class="texto-azul">Tulo</span>buscas? </h2>            
                       <p class="text-justify mt-10">Beneficios de utilizar la plataforma Tulobuscas: ahorras tiempo, ya que la aplicación te muestra las tiendas que tienen el repuesto que necesitas. Además, ahorras dinero al evitar desplazarte por la ciudad buscando de tienda en tienda el repuesto requerido.</p>
                           
                       </p>               
                       <div class="container">
                        <div class="row justify-content-center">
                        <div class="col-md-8 text-center">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item center-bloc" src="https://www.youtube.com/embed/U2lGHJqk778"></iframe>
                            </div>
                        </div>
                        </div>
                   </div>
                    </div>  
                </div>
            </div>

      </div>     

  
    


</x-app-layout>