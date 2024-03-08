<?php
include 'config.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
    // Recuperar os dados do formulário
    $conectado_em = $_POST['conectado_em'];
    $numero_cabo = $_POST['numero_cabo'];
    $local_switch_id = $_POST['local_switch_id'];
    $porta_sw = $_POST['porta_sw'];

    // Inserir os dados na tabela maparede
    $sql_insert = "INSERT INTO maparede (ConectadoEm, Local_SwitchID, NumeroCabo, PortaSw) VALUES ('$conectado_em', $local_switch_id, '$numero_cabo', '$porta_sw')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "<p>Dados cadastrados com sucesso.</p>";
    } else {
        echo "Erro ao cadastrar os dados: " . $conn->error;
    }
}

// Consultar os switches cadastrados para preencher o dropdown
$sql_switches = "SELECT SwitchID, Local_Switch FROM switch";
$result_switches = $conn->query($sql_switches);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapear Rede</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 250px;
            padding: 5px;
            margin-bottom: 10px;
        }

        select {
            width: 260px;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Mapear Rede</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="conectado_em">Conectado em:</label>
        <input type="text" id="conectado_em" name="conectado_em" required>
        <label for="numero_cabo">Número do Cabo:</label>
        <input type="text" id="numero_cabo" name="numero_cabo" required>
        <label for="local_switch_id">Selecione Switch:</label>
        <select name="local_switch_id" id="local_switch_id" required>
            <option value="">Selecione...</option>
            <?php
            if ($result_switches->num_rows > 0) {
                while ($row = $result_switches->fetch_assoc()) {
                    echo "<option value='" . $row['SwitchID'] . "'>" . $row['Local_Switch'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum switch encontrado</option>";
            }
            ?>
        </select>
        <label for="porta_sw">Porta do Switch:</label>
        <input type="text" id="porta_sw" name="porta_sw" required>
        <input type="submit" name="cadastrar" value="Cadastrar">
    </form>
</body>
</html>
