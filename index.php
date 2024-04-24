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
    <title>Sistema de Gestão de TI Morumbi Sul</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        #header {
            background-color: #6abfe0;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        #content {
            padding: 20px;
        }
        iframe {
            border: none;
            width: 100%;
            height: calc(100vh - 60px); /* Ajuste de acordo com a altura do header */
        }
    </style>
</head>
<body>
    <div id="header">
        <a href="inicial.php" target="content">
            <img src="banner.png" alt="Banner" style="max-width: 10%; height: auto;">
        </a>
        <p>Bem-vindo, <?php echo $_SESSION['email']; ?>!</p>
        <a href="logout.php" class="btn btn-primary mr-2">Logout</a>
        <a href="admin/admin.php" class="btn btn-secondary" target="content">Administração</a>
        <a href="chamado/chamados.php" class="btn btn-secondary" target="content">Chamados</a>
        <a href="minha_conta.php" class="btn btn-primary mr-2" target="content">Minha Conta</a>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
               
                <li class="nav-item">
                    <a class="nav-link" href="chamado/historico_chamados.php" target="content">Historico de Chamados</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="registrar_entrada.php" target="content">Registrar Entrada em Produto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registrar_saida.php" target="content">Registrar Saída de Produto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="estoque.php" target="content">Consultar Estoque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="log.php" target="content">Log de Movimentação</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mapear.php" target="content">Mapear Rede</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="maparede.php" target="content">Vizualizar Mapa de Rede</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="emprestimo.php" target="content">Emprestimo de Ativos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cbmovimentacoes.php" target="content">Log de Emprestimos de Ativos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_produto.html" target="content">Cadastro de Ativos para Venda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="baixa_venda.php" target="content">Venda de Ativos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vitrine.php" target="content">Vitrine</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logvendas.php" target="content">Log Vendas</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="content">
        <iframe src="inicial.php" name="content"></iframe>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Função para atualizar a notificação de chamados
        function updateChamadosNotification() {
            $.ajax({
                url: 'get_chamados_count.php', // Arquivo PHP que retorna o número de chamados abertos
                type: 'GET',
                success: function(response) {
                    // Remove qualquer número existente antes de adicionar o novo
                    $('a[href="chamado/chamados.php"]').find('.badge').remove();
                    
                    // Atualiza o número de chamados abertos na notificação
                    var chamadosCount = parseInt(response);
                    if (chamadosCount > 0) {
                        $('a[href="chamado/chamados.php"]').append('<span class="badge badge-pill badge-danger ml-1">' + chamadosCount + '</span>');
                    }
                }
            });
        }

        // Atualiza a notificação de chamados quando a página é carregada
        $(document).ready(function() {
            updateChamadosNotification();
            // Atualiza a notificação a cada 10 segundos
            setInterval(updateChamadosNotification, 1000);
        });
    </script>
<footer>
<?php echo $gitVersion; ?>
</footer>
</body>
</html>
