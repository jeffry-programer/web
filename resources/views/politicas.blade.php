<x-app-layout>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-md-block">
        <div class="container">
          <style>
            .active > a {
              background-color: #007bff !important;
              color: #ffffff !important;
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
              <li class="nav-item active">
                <a class="nav-link" href="{{asset('politicas')}}">Politica de privacidad</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{asset('contacto')}}">Contacto</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{asset('ayuda')}}">Ayuda</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="p-4">
                <h2 class="text-justify mt-5">Al usar en nuestra plataforma, depositas tu confianza en nosotros. Reconocemos la gran responsabilidad que ello implica y nos 
                    comprometemos a proteger tu información con el máximo cuidado</h2>            
                <p class="text-justify mt-10">Como usuario, puedes acceder a una variedad de servicios ofrecidos por la plataforma Tulobuscas. Puedes realizar consultas sobre repuestos y comunicarte directamente con las tiendas. Es importante tener en cuenta que, para acceder a varios de estos servicios, es necesario estar registrado en la plataforma. Del mismo modo, el registro es requisito indispensable para disfrutar de otros servicios disponibles.</p>             
                
                <h2 class="text-justify mt-5">En <span class="texto-azul">Tulo</span>buscas</span>, nos esforzamos por proporcionar transparencia a nuestros usuarios sobre la información que recopilamos cuando utilizan nuestra plataforma</h2>
                <p class="text-justify mt-10">Dependiendo del perfil en nuestra plataforma, se solicitan ciertos datos necesarios para mejorar la experiencia del usuario. Por ejemplo, para un usuario que busca un repuesto, solicitamos datos de ubicación; al registrarse en el sistema, se pide información personal como correo electrónico y contraseña. En el caso de una tienda interesada en unirse a nuestra red, se solicita información de ubicación, detalles sobre los repuestos que ofrecen y datos de contacto. Nos comprometemos a compartir los datos de contacto para facilitar la comunicación entre usuarios y tiendas.</p>

                <h2 class="text-justify mt-5">Mantenimiento de la información</h2>
                <p class="text-justify mt-10">La plataforma de Tulobuscas ofrece a todos los usuarios, sin importar su perfil, la posibilidad de realizar un mantenimiento de la información proporcionada desde el momento del registro. Esto incluye la capacidad de agregar más detalles, editar la información existente o eliminar cualquier dato que el usuario considere innecesario. Mantener la información actualizada es clave para garantizar una experiencia óptima en la plataforma.<br><br>Las empresas también cuentan con la misma funcionalidad y pueden mantener su información actualizada. Tienen la capacidad de actualizar datos directamente relacionados con la tienda, como la ubicación y las imágenes, así como editar detalles de los productos asociados, como precio y cantidad disponibles.</p>

                <h2 class="text-justify mt-5">¿Los usuarios de <span class="texto-azul">Tulo</span>buscas</span> comparten su información?</h2>
                <p class="text-justify mt-10">Los usuarios comparten su información al suscribirse a una tienda, lo que permite a la tienda visualizar quiénes están suscritos a ella. Del mismo modo, las tiendas comparten información al proporcionar datos de contacto para facilitar la comunicación entre los usuarios y la tienda que ofrece los repuestos que necesitan.</p>
            </div> 
            
            
        </div>
        </div>
      </div>
    
      

  
    


</x-app-layout>