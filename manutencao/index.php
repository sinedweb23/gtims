<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenção Morumbi Sul</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .container-fluid {
            height: 100%;
        }
        .row {
            height: 100%;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            padding: 0;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 16px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .iframe-container {
            height: 100%;
        }
        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }
        .badge {
            margin-left: 5px;
            background-color: red;
            color: white;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }
            .navbar-nav {
                display: block;
            }
        }
        @media (min-width: 768px) {
            .navbar-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="banner.png" alt="Sistema de Chamados" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="home.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="chamados.php"><i class="fas fa-ticket-alt"></i> Chamados 
                        <span id="chamadosCount" class="badge badge-danger">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="historico_chamados.php"><i class="fas fa-history"></i> Histórico de Chamados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="usuarios.php"><i class="fas fa-users"></i> Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="minha_conta.php"><i class="fas fa-user"></i> Editar meus dados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="home.php" target="iframe_a"><i class="fas fa-home"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="chamados.php" target="iframe_a"><i class="fas fa-ticket-alt"></i> Chamados 
                                <span id="chamadosCount" class="badge badge-danger">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historico_chamados.php" target="iframe_a"><i class="fas fa-history"></i> Histórico de Chamados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="usuarios.php" target="iframe_a"><i class="fas fa-users"></i> Usuários</a>
                        </li>
                        <li class="nav-item">
                    <a class="nav-link" target="iframe_a" href="minha_conta.php"><i class="fas fa-user"></i> Editar meus dados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 iframe-container">
                <iframe name="iframe_a" src="home.php"></iframe>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Verifica o número de chamados abertos periodicamente
        function checkChamados() {
            fetch('verificar_chamados_abertos.php')
                .then(response => response.json())
                .then(data => {
                    const chamadosCountElement = document.getElementById('chamadosCount');
                    const currentCount = parseInt(chamadosCountElement.textContent);
                    
                    if (data.count > currentCount) {
                        // Toca o som de notificação se houver novos chamados
                        const audio = new Audio('notificacao.mp3');
                        audio.play();
                    }

                    chamadosCountElement.textContent = data.count;
                })
                .catch(error => console.error('Erro ao verificar chamados abertos:', error));
        }

        // Verifica os chamados abertos a cada 10 segundos
        setInterval(checkChamados, 10000);
    });
    </script>
</body>
</html>
