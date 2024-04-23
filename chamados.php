<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fechar_chamado'])) {
    $chamado_id = $_POST['chamado_id'];
    
    // Atualiza o status do chamado para "Fechado" no banco de dados
    $sql = "UPDATE gestao_ti SET status = 'Fechado' WHERE id = $chamado_id";
    if ($conn->query($sql) === TRUE) {
        echo "Chamado fechado com sucesso!";
    } else {
        echo "Erro ao fechar o chamado: " . $conn->error;
    }
}

// Consulta o banco de dados para obter os gestao_ti abertos
$sql = "SELECT c.id, c.nome AS solicitante, st.setor AS nome_setor, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura
        FROM gestao_ti c
        INNER JOIN setor st ON c.id_setor = st.SetorID
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Aberto'  -- Verifica se o chamado está aberto
        ORDER BY c.data_abertura DESC";

$result = $conn->query($sql);


// Obtém a contagem de gestao_ti abertos atualmente
$numgestao_tiAntes = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestao_ti Abertos</title>
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
        <h2 class="mb-4">gestao_ti Abertos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Sala</th>
                    <th>Defeito</th>
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
                if ($result->num_rows > 0) {
                    // Exibe os gestao_ti abertos em uma tabela
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
                        echo "<td><form action='' method='post'><input type='hidden' name='chamado_id' value='".$row["id"]."'><button type='submit' name='fechar_chamado' class='btn btn-primary'>Fechar Chamado</button></form></td>";
                        echo "</tr>";
                    }
                } else {
                    // Se não houver gestao_ti abertos, exibe uma mensagem
                    echo "<tr><td colspan='9'>Nenhum chamado aberto.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Script para buscar novos gestao_ti e exibir notificações -->
    <script>
        // Função para buscar novos gestao_ti e exibir notificações
        function verificarNovosgestao_ti() {
            fetch('get_gestao_ti.php')
                .then(response => response.json())
                .then(gestao_ti => {
                    // Verifica se há novos gestao_ti
                    if (gestao_ti.length > <?php echo $numgestao_tiAntes; ?>) {
                        // Mostra a notificação
                        mostrarNotificacao();
                        // Atualiza a página
                        location.reload();
                    }
                })
                .catch(error => console.error('Erro ao buscar os gestao_ti:', error));
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

        // Verifica novos gestao_ti a cada 10 segundos
        setInterval(verificarNovosgestao_ti, 10000);
    </script>
</body>
</html>
