<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistema Administrativo</title>
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo del cuerpo de la página */
        }

        .navbar {
            background-color: #ffffff; /* Color de fondo del header */
            border-bottom: 1.4rem solid #6495ED; /* Color de la franja justo debajo del header */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo opaco del cuerpo de la página */
            padding: 20px; /* Espaciado interior del cuerpo de la página */
            border-radius: 10px; /* Bordes redondeados del cuerpo de la página */
            margin-top: 20px; /* Margen superior para separar del header */
        }

        .fa-solid{
            margin-left: .5rem;
            margin-right: .5rem;
        }

        a{
            color: #4a4a4a !important;
            font-size: 1.1rem !important;
        }

        a:hover{
            color: #242424 !important;s
        }

        .sub-item{
            margin-left: .5rem;
        }
    </style>
</head>
<body>



<div class="row" style="margin-right: 0px;margin-left: 0px;">
    <div class="col-md-4">
        <div class="container">
            <div class="row">
                @livewire('side-bar')
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="container">
            <!-- Tu contenido va aquí -->
            <h1>Bienvenido al Sistema Administrativo</h1>
        </div>        
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
