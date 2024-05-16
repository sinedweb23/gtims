<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Função para obter a versão do Git
function getGitVersion() {
    // Executa o comando Git para obter a versão.
    $version = shell_exec("git describe --tags --abbrev=0 | sed 's/v//'");
    // Retorna a versão obtida.
    return "Versão v$version";
}

// Obtém a versão do Git
$gitVersion = getGitVersion();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de TI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            padding: 15px;
            height: 100vh;
            position: relative; /* Needed for absolute positioning of submenus */
        }
        .sidebar a, .sidebar a:hover {
            color: #fff;
            text-decoration: none;
        }
        .sidebar ul {
            list-style-type: none; /* Remove list bullets */
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            padding: 10px;
            margin: 5px 0;
            position: relative; /* For submenu positioning */
        }
        .sidebar li:hover {
            background: #495057;
        }
        .submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            width: 150px; /* Width of the submenu */
            background: #343a40;
        }
        .submenu li {
            display: block;
        }
        .sidebar li:hover .submenu {
            display: block; /* Display submenu on hover */
        }
        .content {
            flex-grow: 1;
            overflow-y: auto;
        }
        header {
            background: #f8f9fa;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        iframe {
            width: 100%;
            height: 100%;
            height: calc(100vh - 56px);
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="banner.png" alt="Logo" class="img-fluid">
        </div>
        <ul>
            <li><a href="chamado/chamados.php" target="iframe"><i class="fas fa-home"></i> Chamados <span id="chamadosNotification" class="badge badge-pill badge-danger ml-1"></span></a></li>
            <li><a href="chamado/historico_chamados.php" target="iframe"><i class="fas fa-history"></i> Histórico de Chamados</a></li>
            <li>
                <a href="#"><i class="fas fa-boxes"></i> Estoque</a>
                <ul class="submenu">
                    <li><a href="registrar_entrada.php" target="iframe">Entrada</a></li>
                    <li><a href="registrar_saida.php" target="iframe">Saída</a></li>
                    <li><a href="estoque.php" target="iframe">Saldo</a></li>
                    <li><a href="log.php" target="iframe">Log</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fas fa-network-wired"></i> Redes</a>
                <ul class="submenu">
                    <li><a href="mapear.php" target="iframe">Mapear</a></li>
                    <li><a href="maparede.php" target="iframe">Visualizar</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fas fa-laptop"></i> Ativos</a>
                <ul class="submenu">
                    <li><a href="emprestimo.php" target="iframe">Empréstimo</a></li>
                    <li><a href="cbmovimentacoes.php" target="iframe">Log</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fas fa-store"></i> Vitrine</a>
                <ul class="submenu">
                    <li><a href="cadastro_produto.html" target="iframe">Cadastros</a></li>
                    <li><a href="baixa_venda.php" target="iframe">Venda</a></li>
                    <li><a href="logvendas.php" target="iframe">Log</a></li>
                    <li><a href="vitrine.php" target="iframe">Vitrine</a></li>
                </ul>
            </li>
            <li><a href="admin/admin.php" target="iframe"><i class="fas fa-cogs"></i> Administração</a></li>
            <li><a href="minha_conta.php" target="iframe"><i class="fas fa-user"></i> Minha conta</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <header>
            <div></div>
            <div>Bem-vindo, <?php echo $_SESSION['email']; ?></div>
            <div><?php echo $gitVersion; ?></div>
        </header>
        <iframe name="iframe" src="inicial.php"></iframe>
    </div>
    <audio id="notificationSound" src="notification_sound.mp3" preload="auto"></audio>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        var lastCount = 0;

        function updateChamadosNotification() {
            $.ajax({
                url: 'get_chamados_count.php', // Arquivo PHP que retorna o número de chamados abertos
                type: 'GET',
                success: function(response) {
                    var currentCount = parseInt(response);
                    $('#chamadosNotification').text(currentCount);

                    // Toca o som de notificação se o número de chamados abertos aumentou
                    if (currentCount > lastCount) {
                        document.getElementById('notificationSound').play();
                    }
                    lastCount = currentCount;
                }
            });
        }

        // Atualiza a notificação de chamados quando a página é carregada
        $(document).ready(function() {
            updateChamadosNotification();
            // Atualiza a notificação a cada 10 segundos
            setInterval(updateChamadosNotification, 10000);
        });
    </script>
</body>
</html>

