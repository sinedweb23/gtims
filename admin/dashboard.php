<?php include '../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <!-- Inclua a biblioteca Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Painel Administrativo</h1>

    <form action="centro_custos_info.php" method="get">
        <label for="id_centro_custos">Selecione um Centro de Custos:</label>
        <select name="id_centro_custos" id="id_centro_custos">
            <?php
            $sql_centro_custos_dropdown = "SELECT id, nome FROM centro_custos";
            $result_centro_custos_dropdown = $conn->query($sql_centro_custos_dropdown);
            if ($result_centro_custos_dropdown->num_rows > 0) {
                while($row = $result_centro_custos_dropdown->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum centro de custos encontrado</option>";
            }
            ?>
        </select>
        <button type="submit">Selecionar</button>
    </form>

    <!-- Div para o gráfico de todos os centros de custos -->
    <div style="width: 800px; margin: 20px auto;">
        <canvas id="allCentersChart"></canvas>
    </div>

    <!-- Incluindo os gráficos de todos os centros de custos -->
    <?php include 'graficos_centro_custos.php'; ?>

</body>
</html>
