<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <img class="img-fluid" style="width: 16rem;" src=" {{ asset('images/piePagiina.png') }} " alt="..">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav"  style="justify-content: right;">
            <ul class="navbar-nav ml-auto" style="margin-right: 1rem;">
                <li class="nav-item active">
                    <a class="nav-link" style="color: #ffffff !important" href="/admin">Inicio</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: .1rem;" x-data>
                        @csrf
                        <button class="btn nav-link" aria-current="page"><i class="fa-solid fa-right-to-bracket" style="margin-right:.5rem"></i>Cerrar sesión</button>
                    </form>
                  </li>
                <!-- Puedes agregar más elementos de menú según tus necesidades -->
            </ul>
        </div>
    </nav>
</div>
