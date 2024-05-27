<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Página Responsiva</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .logo {
            width: 200px;
            margin-bottom: 30px;
        }
        .menu-item {
            margin: 15px;
        }
        .menu-item img {
            width: 100px;
        }
        .menu-item h4 {
            font-size: 1rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <img src="logotipoms.png" alt="Logo" class="logo img-fluid">
        <div class="row justify-content-center">
            <div class="col-6 col-sm-4 col-md-3 menu-item">
                <a href="../reserva.php">
                    <img src="cb.png" alt="Reserva de Chromebook" class="img-fluid">
                    
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 menu-item">
                <a href="abertura_chamado_sala.php">
                    <img src="sup.png" alt="Suporte Técnico" class="img-fluid">
                    
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
