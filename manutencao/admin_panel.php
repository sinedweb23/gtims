<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Painel Admin</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="chamados.php" class="list-group-item list-group-item-action">Chamados Abertos</a>
                    <a href="#" class="list-group-item list-group-item-action">Chamados Atendidos</a>
                    <a href="#" class="list-group-item list-group-item-action">Cadastrar Usuário</a>
                    <a href="#" class="list-group-item list-group-item-action">Cadastrar Defeitos</a>
                    <a href="#" class="list-group-item list-group-item-action">Cadastrar Dúvidas Frequentes</a>
                    <a href="#" class="list-group-item list-group-item-action">Cadastrar Salas</a>
                </div>
            </div>
            <div class="col-md-9">
                <iframe src="chamados.php" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        </div>
        <a href="logout.php" class="btn btn-danger mt-3">Sair</a> <!-- Link para a página de logout -->
    </div>
</body>
</html>
