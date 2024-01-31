<x-app-layout>
    @section('css')
    <style>
         .card {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombras en el borde */

        }
        .card-img {
            flex: 0 0 auto;
            width: 50%;
            overflow: hidden;
         }
        .card-text-container {
            flex: 1 1 auto;
            overflow: auto;
            padding: 1rem;
        }
    </style>
    @endsection

    <div class="row">
        <div class="container" style="padding-inline: 12vw !important;padding-top: 3vw;">
            <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-6">
                    <img style="height: 100%;
                    object-fit: cover;" src=" {{ asset($publicity->image) }} " class="img-fluid rounded-start" alt="...">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 col-md-9">
                                <a href="#"><p style="font-size: .74rem;text-decoration: underline;color: #666;">http://127.0.0.1:8000/publicities/6</p></a>
                            </div>
                            <div class="col-4 col-md-3">
                                <button class="btn btn-primary" style="font-size: .6rem;display: flex;"><i class="fa-solid fa-bell me-1" style="margin-top: .2rem;"></i>Suscribete</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title">Título de la Tarjeta</h5>
                                <p class="card-text">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec lacus vitae odio rutrum vehicula eu non massa. Nullam aliquet metus at consectetur dapibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris dapibus ligula sed urna aliquet, a convallis ex tincidunt. Nulla facilisi. Integer pulvinar aliquam odio, vel tincidunt sapien. Duis nec velit ultricies, commodo ex sit amet, dapibus risus. Mauris maximus vulputate dolor, vel hendrerit tortor malesuada nec. Integer ut malesuada nisi.
                                </p>                            
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row text-center pb-3">
            <h4>Más publicidad</h4>
        </div>

        <div class="row px-5">
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div> 
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div>
            </div>
        </div>
        <div class="row px-5 pt-3">
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div> 
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTV8fHxlbnwwfHx8fHw%3D" class="img-fluid rounded-start" alt="...">
                </div>
            </div>
        </div>


    </div>
</x-app-layout>