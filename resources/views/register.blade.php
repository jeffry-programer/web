<x-app-layout>
    <div class="row py-5 text-center">
        <h2>Registrate en tulobuscas</h2>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container">
                    <img class="zoomed-image" src="{{ asset('images/user.jpg') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu usuario</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Descubre los beneficios de ser usuario de tulobuscas</p>
                    <button class="btn w-100" onclick="window.location.replace('register')" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;">Ir al registro</button>
                  </div>
            </div>
        </div>
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container">
                    <img class="zoomed-image" src="{{ asset('images/tienda.jfif') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu tienda</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Como tienda podras mostrar tus repuestos a la gran red de usuarios de tulobuscas</p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;">Ir al registro</button>
                  </div>
            </div>
        </div>
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container">
                    <img class="zoomed-image" src="{{ asset('images/taller.jpg') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu taller</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Atrae más clientes gracias a la tecnologia y aumenta tus ingresos</p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;">Ir al registro</button>
                  </div>
            </div>
        </div>
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container">
                    <img class="zoomed-image" src="{{ asset('images/gruaSinfondo.png') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu grua</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Aumenta tus ingresos y ayuda a los usuarios en momento de una necesidad de transporte</p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;">Ir al registro</button>
                  </div>
            </div>
        </div>
    </div>
</x-app-layout>