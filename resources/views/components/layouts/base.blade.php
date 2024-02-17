<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Metas -->
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Tulobuscas
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @livewireStyles

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

    @yield('css')

</head>

<body class="g-sidenav-show bg-gray-100">

    {{ $slot }}

    <!--   Core JS Files   -->
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    @livewireScripts
    @yield('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $("#menu").click(() => {
            if($("#menu").attr('class').includes('act')){
                $("#menu").removeClass('act');
                $(".item-bd").fadeOut();
            }else{
                $("#menu").addClass('act');
                $(".item-bd").fadeIn();
            }
        });

        $("#menu2").click(() => {
            if($("#menu2").attr('class').includes('act')){
                $("#menu2").removeClass('act');
                $(".item-bd2").fadeOut();
            }else{
                $("#menu2").addClass('act');
                $(".item-bd2").fadeIn();
            }
        });

        $("#menu3").click(() => {
            if($("#menu3").attr('class').includes('act')){
                $("#menu3").removeClass('act');
                $(".item-bd3").fadeOut();
            }else{
                $("#menu3").addClass('act');
                $(".item-bd3").fadeIn();
            }
        });
    </script>
</body>

</html>
