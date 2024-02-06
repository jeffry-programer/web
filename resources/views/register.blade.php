<x-app-layout>
    <div class="row py-5 text-center">
        <h2>Registrate en <b style="color: #6495ed;font-size: 2rem;">Tulobuscas</b></h2>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container" style="cursor: pointer" onclick="window.location.replace('register')">
                    <img class="zoomed-image" src="{{ asset('images/user.jpg') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu usuario</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Encuentra fácil y rapido el repuesto o accesorio que necesitas, al registrarte en <b style="color: #6495ed;font-size: 1.2rem;">Tulobuscas</b></p>
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
                  <div class="zoom-container" style="cursor: pointer" onclick="window.location.replace('register-store')">
                    <img class="zoomed-image" src="{{ asset('images/tienda.jfif') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu tienda</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Como tienda podrás mostrar tus repuestos a la gran red de usuarios de <b style="color: #6495ed;font-size: 1.2rem;">Tulobuscas</b></p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;" onclick="window.location.replace('register-store')">Ir al registro</button>
                  </div>
            </div>
        </div>
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container" style="cursor: pointer" onclick="window.location.replace('register-taller')">
                    <img class="zoomed-image" src="{{ asset('images/taller.jpg') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu taller</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Potencia tu negocio y eleva tus ganancias atrayendo a una mayor cantidad de clientes con <b style="color: #6495ed;font-size: 1.2rem;">Tulobuscas</b></p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;" onclick="window.location.replace('register-taller')">Ir al registro</button>
                  </div>
            </div>
        </div>
        <div class="col-12 col-md-3 mt-3">
            <div class="card card-store mb-5" style="height: 25.4rem;">
                  <div class="zoom-container" style="cursor: pointer" onclick="window.location.replace('register-grua')">
                    <img class="zoomed-image" src="{{ asset('images/gruaSinfondo.png') }}" alt="Descripción de la imagen">
                  </div>
                  <div class="card-body" style="padding-bottom: .5rem;position: relative;">
                    <h5 class="card-title text-center fw-bolder">Registra tu grua</h5>
                    <p class="card-text" style="color: #86878a;
                    text-align: justify;">Brinda asistencia en un momento de emergencia y ayuda a los usuarios a través de <b style="color: #6495ed;font-size: 1.2rem;">Tulobuscas</b> </p>
                    <button class="btn w-100" style="background: #00a3e8;
                    color: white;
                    position: absolute;
                    bottom: 1rem;
                    right: rem;
                    width: 18rem !important;" onclick="window.location.replace('register-grua')">Ir al registro</button>
                  </div>
            </div>
        </div>
    </div>
</x-app-layout>