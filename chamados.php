<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Definindo $result como vazio inicialmente.
$result = null;

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fechar_chamado'])) {
    $chamado_id = $_POST['chamado_id'];
    $solucao = isset($_POST['solucao']) ? $_POST['solucao'] : ''; // Solução digitada pelo usuário
    
    // Obtém a data e hora atual
    $data_fechamento = date('Y-m-d H:i:s');
    
    // Atualiza o status do chamado para "Fechado", a data de fechamento e a solução no banco de dados
    $sql = "UPDATE chamados SET status = 'Fechado', data_fechamento = '$data_fechamento', solucao = '$solucao' WHERE id = $chamado_id";
    if ($conn->query($sql) === TRUE) {
        echo "Chamado fechado com sucesso!";
    } else {
        echo "Erro ao fechar o chamado: " . $conn->error;
    }
}

// Consulta o banco de dados para obter os chamados abertos
$sql = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura, c.data_fechamento
        FROM chamados c
        INNER JOIN salas s ON c.id_sala = s.id
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Aberto'  -- Verifica se o chamado está aberto
        ORDER BY c.data_abertura DESC";

// Executa a consulta
$result = $conn->query($sql);

// Obtém a contagem de chamados abertos atualmente
$numChamadosAntes = $result->num_rows ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados Abertos</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo condicional para prioridade alta */
        .prioridade-alta {
            background-color: #eab6b6; /* Vermelho claro */
        }
        
        /* Estilo condicional para prioridade média */
        .prioridade-media {
            background-color: #f9f7b3; /* Amarelo claro */
        }
        
        /* Estilo condicional para prioridade baixa */
        .prioridade-baixa {
            background-color: #97ee92; /* Verde claro */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Chamados Abertos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Sala</th>
                    <th>Problema</th>
                    <th>Prioridade</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Data de Abertura</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica se a consulta retornou resultados
                if ($result && $result->num_rows > 0) {
                    // Exibe os chamados abertos em uma tabela
                    while($row = $result->fetch_assoc()) {
                        // Define a classe de estilo com base na prioridade do chamado
                        $prioridade_class = '';
                        switch ($row["prioridade"]) {
                            case 'alto':
                                $prioridade_class = 'prioridade-alta';
                                break;
                            case 'medio':
                                $prioridade_class = 'prioridade-media';
                                break;
                            case 'baixo':
                                $prioridade_class = 'prioridade-baixa';
                                break;
                            default:
                                $prioridade_class = '';
                        }
                        
                        // Exibe o chamado na tabela com a classe de estilo condicional
                        echo "<tr class='".$prioridade_class."'>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["solicitante"]."</td>";
                        echo "<td>".$row["nome_sala"]."</td>";
                        echo "<td>".$row["nome_defeito"]."</td>";
                        echo "<td>".$row["prioridade"]."</td>";
                        echo "<td>".$row["observacao"]."</td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["data_abertura"]."</td>";
                        echo "<td><button onclick='fecharChamado(".$row["id"].")' class='btn btn-primary'>Fechar Chamado</button></td>";
                        echo "</tr>";
                    }
                } else {
                    // Se não houver chamados abertos, exibe uma mensagem
                    echo "<tr><td colspan='9'>Nenhum chamado aberto.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Script para buscar novos chamados e exibir notificações -->
    <script>
        function fecharChamado(id) {
            var solucao = prompt("Digite a solução adotada para fechar o chamado:");
            if (solucao !== null) {
                // Envia a solução adotada para o backend
                fetch('fechar_chamado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        chamado_id: id,
                        solucao: solucao
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Chamado fechado com sucesso!");
                        location.reload();
                    } else {
                        alert("Erro ao fechar o chamado: " + data.error);
                    }
                })
                .catch(error => console.error('Erro ao fechar o chamado:', error));
            }
        }
    </script>
      <!-- Script para buscar novos chamados e exibir notificações -->
      <script>
    // Função para buscar novos chamados e exibir notificações
    function verificarNovosChamados() {
        fetch('get_chamados.php')
            .then(response => response.json())
            .then(chamados => {
                // Verifica se há novos chamados
                if (chamados.length > <?php echo $numChamadosAntes; ?>) {
                    // Mostra a notificação
                    mostrarNotificacao();
                    // Atualiza a página
                    location.reload();
                }
            })
            .catch(error => console.error('Erro ao buscar os chamados:', error));
    }

    // Função para mostrar a notificação
    function mostrarNotificacao() {
        // Verifica se o navegador suporta notificações
        if (!("Notification" in window)) {
            console.log("Este navegador não suporta notificações.");
        } else if (Notification.permission === "granted") {
            // Cria a notificação
            var notification = new Notification("Novo chamado!", {
                body: "Um novo chamado foi aberto.",
                icon: "notification_icon.png"
            });
        } else if (Notification.permission !== 'denied') {
            // Solicita permissão ao usuário para mostrar notificações
            Notification.requestPermission().then(function (permission) {
                // Se o usuário permitir, mostra a notificação
                if (permission === "granted") {
                    var notification = new Notification("Novo chamado!", {
                        body: "Um novo chamado foi aberto.",
                        icon: "notification_icon.png"
                    });
                }
            });
        }
    }

    // Verifica novos chamados a cada 10 segundos
    setInterval(verificarNovosChamados, 10000);
</script>

</body>
</html>
