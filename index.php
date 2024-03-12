<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}
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
            <li id="menurede"><a href="mapear.php" target="content">Mapear Rede</a></li>
            <li id="menurede"><a href="maparede.php" target="content">Vizualizar Mapa de Rede</a></li>
            <li id="menurede"><a href="emprestimo.php" target="content">Emprestimo de ChromeBook</a></li>
            <li id="menurede"><a href="cbmovimentacoes.php" target="content">Log de Emprestimos de Ativos</a></li>
            
        </ul>
    </div>

    

    <div id="content">
        <iframe src="inicial.php" name="content"></iframe>
    </div>
</body>
</html>
