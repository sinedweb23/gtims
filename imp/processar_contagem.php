<?php
// Incluir arquivo de configuração
require_once('config.php');

// Verificar se foram enviados os totais de folhas impressas
if(isset($_POST['total_impressas'])) {
    $total_impressas_por_impressora = $_POST['total_impressas'];

    foreach ($total_impressas_por_impressora as $impressora_id => $total_impressas) {
        $impressora_id = intval($impressora_id);
        $total_impressas = intval($total_impressas);

        // Consultar as contagens atuais e anteriores de impressão para esta impressora
        $sql_contagens = "SELECT contagem_atual, contagem_anterior FROM impressoras WHERE id = $impressora_id";
        $result_contagens = $conn->query($sql_contagens);
        $row_contagens = $result_contagens->fetch_assoc();

        $contagem_atual = $row_contagens['contagem_atual'];
        $contagem_anterior = $row_contagens['contagem_anterior'];

        // Calcular a diferença entre o total atual e o total anterior
        $diferenca = $total_impressas - $contagem_anterior;

        // Se a diferença for negativa, ajustar para zero (evita contagens negativas)
        $diferenca = max(0, $diferenca);

        // Inserir a diferença na tabela impressoes_mensais apenas se for diferente de zero
        if ($diferenca > 0) {
            // Inserir a diferença na tabela impressoes_mensais
            $mes_atual = date('n');
            $ano_atual = date('Y');
            $sql_insert = "INSERT INTO impressoes_mensais (impressora_id, mes, ano, quantidade) VALUES ($impressora_id, $mes_atual, $ano_atual, $diferenca)";
            $conn->query($sql_insert);
        }

        // Atualizar contagem atual e contagem anterior no banco de dados
        $sql_update = "UPDATE impressoras SET contagem_anterior = $contagem_atual, contagem_atual = $total_impressas WHERE id = $impressora_id";
        $conn->query($sql_update);
    }

    echo "Totais de folhas impressas inseridos com sucesso para as impressoras.";
} else {
    echo "Erro: Nenhum total de folhas impressas foi enviado.";
}

// Fechar conexão com o banco de dados
$conn->close();
?>
