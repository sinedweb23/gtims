<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Responsivo</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px; /* Altura da barra de navegação */
        }
    </style>
</head>
<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php" target="iframe_content">Página Inicial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adiciona_switch.php" target="iframe_content">Adicionar Switch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adicionar_usuario.php" target="iframe_content">Adicionar Usuário</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categoria_setor.php" target="iframe_content">Adicionar Categoria e Setor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php" target="iframe_content">Lista de Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="centro_custo.php" target="iframe_content">Centro de Custos</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo Principal com iframe -->
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <iframe name="iframe_content" src="dashboard.php" frameborder="0" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e jQuery (opcional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
