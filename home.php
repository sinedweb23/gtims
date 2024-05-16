<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Evitar rolagem nas páginas */
        }
        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }
        .nav-tabs {
            flex-shrink: 0;
        }
        .tab-content {
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .tab-pane {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Evitar rolagem dentro das abas */
            padding: 0; /* Remover padding para garantir que o iframe ocupe toda a área */
        }
        .tab-pane iframe {
            flex-grow: 1;
            border: none;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Garantir que o iframe ocupe toda a área disponível */
            margin: 0; /* Remover margens */
            padding: 0; /* Remover padding */
        }
    </style>
</head>
<body>
    <div class="container">
        <ul class="nav nav-tabs" id="chamadosTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="chamados-tab" data-toggle="tab" href="#chamados" role="tab" aria-controls="chamados" aria-selected="true">Chamados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="historico-tab" data-toggle="tab" href="#historico" role="tab" aria-controls="historico" aria-selected="false">Histórico de Chamados</a>
            </li>
        </ul>
        <div class="tab-content" id="chamadosTabContent">
            <div class="tab-pane fade show active" id="chamados" role="tabpanel" aria-labelledby="chamados-tab">
                <!-- Conteúdo da aba Chamados -->
                <iframe src="chamado/chamados.php"></iframe>
            </div>
            <div class="tab-pane fade" id="historico" role="tabpanel" aria-labelledby="historico-tab">
                <!-- Conteúdo da aba Histórico de Chamados -->
                <iframe src="chamado/historico_chamados.php"></iframe>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
