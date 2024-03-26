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
    // Executa o comando Git para obter a versão
    $version = shell_exec('git describe --tags');
    // Retorna a versão obtida
    return trim($version);
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
            width: 200px;
            padding: 20px;
            float: left;
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
            margin-left: 220px;
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
</a>


        <p>Bem-vindo, <?php echo $_SESSION['email']; ?>!</p>
        <a href="logout.php" style="color: #fff;">Logout</a>
        <a href="admin.php" style="color: #aaa;" target="content">Administrção</a>
    </div>

    <div id="menu">
        
        <ul>
            <li><a href="registrar_entrada.php" target="content">Registrar Entrada em Produto</a></li>
            <li><a href="registrar_saida.php" target="content">Registrar Saída de Produto</a></li>
            <li><a href="estoque.php" target="content">Consultar Estoque</a></li>
            <li><a href="log.php" target="content">Log de Movimentação</a></li>
            <li><a href="mapear.php" target="content">Mapear Rede</a></li>
            <li><a href="maparede.php" target="content">Vizualizar Mapa de Rede</a></li>
            <li><a href="emprestimo.php" target="content">Emprestimo de Ativos</a></li>
            <li><a href="cbmovimentacoes.php" target="content">Log de Emprestimos de Ativos</a></li>
            <li><a href="cadastro_produto.html" target="content">Cadastro de Ativos para Venda</a></li>
            <li><a href="baixa_venda.php" target="content">Venda de Ativos</a></li>
            <li><a href="vitrine.php" target="content">Vitrine</a></li>
            <li><a href="logvendas.php" target="content">Log Vendas</a></li>
        </ul>
        <?php echo $gitVersion; ?>
    </div>

    

    <div id="content">
        <iframe src="inicial.php" name="content"></iframe>
    </div>
</body>
</html>
