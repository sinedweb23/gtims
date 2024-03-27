<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Verificar a permissão do usuário
if ($_SESSION['permissao'] !== 1 && $_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 3) {
    // Se a permissão não for 1 (usuário normal), 2 (admin) ou 3 (super-admin), redirecionar para página de acesso não autorizado
    header("Location: acesso_nao_autorizado.php");
    exit();
}

// Função para obter a versão do Git
function getGitVersion() {
    // Executa o comando Git para obter a versão.
    $version = shell_exec("git describe --tags --abbrev=0 | sed 's/v//'");
    // Retorna a versão obtida..
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
        #menu {
            background-color: #141454;
            color: #fff;
            padding: 20px;
            height: 100vh;
        }
        #menu ul {
            list-style-type: none;
            padding: 0;
        }
        #menu ul li {
            margin-bottom: 10px;
        }
        #menu ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 5px;
            border-radius: 5px;
            background-color: #292a6f;
        }
        #menu ul li a:hover {
            background-color: #888;
        }
        #content {
            padding: 20px;
        }
        iframe {
            border: none;
            width: 100%;
            height: 100vh;
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
        <a href="admin.php" class="btn btn-secondary" target="content">Administração</a>
        <a href="minha_conta.php" class="btn btn-primary mr-2" target="content">Minha Conta</a>

    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
