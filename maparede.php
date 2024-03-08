<?php
include 'config.php';

// Fazer a consulta ao banco de dados para obter os dados do mapa de rede
$sql_mapa_rede = "SELECT m.*, s.Local_Switch, s.Numero_Serie, s.Ip, s.Modelo 
                  FROM maparede m
                  INNER JOIN switch s ON m.Local_SwitchID = s.SwitchID";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    $filtro = $_POST['filtro'];
    $termo = $_POST['termo'];

    $sql_mapa_rede .= " WHERE $filtro LIKE '%$termo%'";
}

$result_mapa_rede = $conn->query($sql_mapa_rede);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Rede</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        form select, form input[type="text"], form button {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #e0e0e0;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Mapa de Rede</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="filtro">Filtrar por:</label>
        <select name="filtro" id="filtro">
            <option value="ConectadoEm">Conectado em</option>
            <option value="NumeroCabo">Anilha do Cabo</option>
            <option value="Local_Switch">Local Switch</option>
        </select>
        <input type="text" name="termo" placeholder="Digite o termo">
        <button type="submit" name="filter">Filtrar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Conectado em</th>
                <th>Anilha do Cabo</th>
                <th>Local Switch</th>
                <th>Número de Série</th>
                <th>IP</th>
                <th>Modelo</th>
                <th>Número da Porta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_mapa_rede && $result_mapa_rede->num_rows > 0) {
                while ($row = $result_mapa_rede->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['ConectadoEm']}</td>";
                    echo "<td>{$row['NumeroCabo']}</td>";
                    echo "<td>{$row['Local_Switch']}</td>";
                    echo "<td>{$row['Numero_Serie']}</td>";
                    echo "<td><a href=\"{$row['Ip']}\" target=\"_blank\">{$row['Ip']}</a></td>"; // Link para o IP
                    echo "<td>{$row['Modelo']}</td>";
                    echo "<td>{$row['PortaSw']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum resultado encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
