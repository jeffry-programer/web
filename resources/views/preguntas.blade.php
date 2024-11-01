<x-app-layout>

    <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-md-block">
        <div class="container">
            <style>
                .active>a {
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
                        <a class="nav-link" href="{{ asset('terminos') }}">Términos y condiciones</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ asset('preguntas') }}">Preguntas frecuentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('politicas') }}">Politica de privacidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('contacto') }}">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('ayuda') }}">Ayuda</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="p-4">
                    <h2 class="text-justify mt-5">¿Que es <span class="texto-azul">Tulo</span>buscas?</h2>
                    <p class="text-justify mt-10">Tulobuscas es una plataforma útil diseñada para facilitar la búsqueda
                        de una amplia variedad de repuestos para vehículos, motocicletas y camiones. Nuestra plataforma
                        te permite explorar un extenso listado de tiendas que ofrecen los productos que necesitas,
                        simplificando así tu proceso de compra. Además, Tulobuscas establece un eficiente canal de
                        comunicación entre la tienda y el usuario que realiza la búsqueda, asegurando una experiencia
                        fluida y satisfactoria para ambas partes</p>

                    <h2 class="text-justify mt-5">¿Quien tiene acceso a mis datos?</h2>
                    <p class="text-justify mt-10">Tulobuscas mantiene una estricta política de privacidad en relación
                        con la información de sus usuarios. Solo los propios usuarios tienen acceso a su información
                        personal, y son responsables de mantenerla actualizada. Esta información se gestiona a través de
                        los perfiles de usuario, garantizando así la confidencialidad y seguridad de los datos.</p>

                    <h2 class="text-justify mt-5">¿Que beneficios tengo como usuario deTulobuscas?</h2>
                    <p class="text-justify mt-10">Los usuarios registrados en nuestra plataforma disfrutan de numerosos
                        beneficios, entre ellos el ahorro de tiempo y dinero. Al realizar consultas de repuestos a
                        través de nuestro sistema, reciben información detallada sobre las tiendas que disponen de
                        dichos productos. Esto elimina la necesidad de recorrer la ciudad en busca de un repuesto
                        específico, ya que el sistema proporciona una lista precisa de las tiendas disponibles. Este
                        enfoque eficiente no solo ahorra tiempo, sino también dinero en combustible, contribuyendo así a
                        una experiencia más conveniente y económica para nuestros usuarios.</p>

                    <h2 class="text-justify mt-5">¿Lo talleres mecánicos son recomendados?</h2>
                    <p class="text-justify mt-10">Los talleres mecánicos registrados en la plataforma de Tulobuscas
                        proporcionan a los usuarios información valiosa para tomar decisiones informadas. Además de una
                        calificación que refleja la satisfacción de los clientes, cada taller cuenta con comentarios
                        detallados que comparten las experiencias de otros usuarios. Estos testimonios ofrecen una
                        visión completa de la calidad del servicio, permitiendo a los usuarios evaluar si desean optar
                        por los servicios de un taller en particular. En última instancia, esta transparencia fomenta
                        una relación de confianza entre talleres y usuarios, garantizando una experiencia satisfactoria
                        para ambas partes.</p>
                </div>
            </div>
        </div>
    </div>







</x-app-layout>
