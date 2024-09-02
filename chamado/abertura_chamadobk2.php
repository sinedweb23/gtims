<?php


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Chamados</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        .container {
            text-align: center;
            margin-top: 10px;
        }
        .btn-img {
            display: block;
            margin: 10px auto;
            max-width: 100px;
        }
        .logo {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" class="logo img-fluid" alt="Logo" width="150">
        <div class="row">
            <div class="col-6">
                <a href="abertura_chamado_sala.php">
                    <img src="ti.png" class="btn-img img-fluid" alt="TI">
                </a>
            </div>
            <div class="col-6">
                <a href="../manutencao/abertura_chamado.php">
                    <img src="manu.png" class="btn-img img-fluid" alt="Manutenção">
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="../reserva.php">
                    <img src="cb.png" class="btn-img img-fluid" alt="Reserva">
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
