<div>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" style="background-image: url('{{ asset('images/1.jpg') }}');background-size: 100% 100%;background-repeat: no-repeat;">
          <div class="carousel-item active">
            <img src="{{ asset('images/2.png') }}" class="d-block w-50 img-carrusel" style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
          </div>
          <div class="carousel-item">
            <img src="{{ asset('images/3.png') }}" class="d-block w-50 img-carrusel" style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
          </div>
          <div class="carousel-item">
            <img src="{{ asset('images/4.png') }}" class="d-block w-50 img-carrusel" style="width: 50% !important;margin-left: 25%;object-fit: contain;" alt="...">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="row">
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="card">
          <div class="card-body" style="background: #f3f4f6;">
            <img class="img-fluid" src="{{ asset('images/2.png') }}" alt="..">
          </div>
        </div>
      </div>
    </div>
    <div class="alert alert-secondary mx-3" role="alert">
      <h6 style="font-size: 1.2rem;margin-top: 0.5rem;">Tiendas con promociones</h6>
    </div>
      <div class="container">
        <div class="row">
          @foreach ($stores as $store)
          <div class="col-12 col-md-4 mt-3">
            <a href="/tienda/{{ str_replace(' ','-', $store->name) }}">
              <div class="card card-store">
                  <img src="{{ asset('http://127.0.0.1:8000'.$store->image) }}" class="card-img-top" alt="...">
                  <div class="card-body" style="padding-bottom: 4rem;">
                  <h5 class="card-title">{{$store->name}}</h5>
                  <p class="card-text">{{$store->description}}</p>
                  <p class="position-absolute bottom-0 start-0" style="padding: 1rem;"><i class="fa-solid fa-location-dot me-1"></i>{{$store->address}}</p>
                  <a href="/tienda/{{ str_replace(' ','-', $store->name) }}" class="btn btn-warning position-absolute bottom-0 end-0" style="/*! padding: ; */margin: .5rem;cursor: pointer;">Ver</a>
                  </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
      </div>
</div>
