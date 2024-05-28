<?php
include 'db_connect.php';

$reserva_success = false;
$reserva_error = false;
$data_reserva = $hora_retirada = $hora_devolucao = $email_username = $andar_id = $sala_id = '';
$ativos_quantidade = 0;
$ativos_disponiveis = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_reserva = $_POST['data_reserva'];
    $hora_retirada = $_POST['hora_retirada'];
    $hora_devolucao = $_POST['hora_devolucao'];
    $email_username = $_POST['email_username'];
    $email_professor = $email_username . '@morumbisul.com.br';
    $andar_id = $_POST['andar_id'];
    $sala_id = $_POST['sala_id'];
    $ativos_quantidade = $_POST['ativos_quantidade'];

    // Capturar data e hora atuais
    $current_datetime = new DateTime();
    
    // Combinar data e hora da retirada para verificar se é futuro
    $reserva_datetime = DateTime::createFromFormat('Y-m-d H:i', $data_reserva . ' ' . $hora_retirada);

    if ($reserva_datetime < $current_datetime) {
        $reserva_error = true;
        $error_message = 'A data e hora da reserva devem ser futuras.';
    } else {
        // Verificar se andar_id e sala_id são válidos
        $stmt = $conn_chamado->prepare("SELECT COUNT(*) FROM andares WHERE id = :andar_id");
        $stmt->execute(['andar_id' => $andar_id]);
        $andar_existe = $stmt->fetchColumn();

        $stmt = $conn_chamado->prepare("SELECT COUNT(*) FROM salas WHERE id = :sala_id AND id_andar = :andar_id");
        $stmt->execute(['sala_id' => $sala_id, 'andar_id' => $andar_id]);
        $sala_existe = $stmt->fetchColumn();

        if ($andar_existe && $sala_existe) {
            // Buscar quantidade de ativos disponíveis
            $sql = "SELECT COUNT(*) as quantidade FROM chromebooks WHERE ID NOT IN (
                        SELECT ativo_id FROM reservas 
                        WHERE data_reserva = :data_reserva
                        AND (
                            (hora_retirada < :hora_devolucao AND hora_devolucao > :hora_retirada)
                        )
                    )";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->execute([
                'data_reserva' => $data_reserva,
                'hora_retirada' => $hora_retirada,
                'hora_devolucao' => $hora_devolucao
            ]);
            $ativos_disponiveis = $stmt->fetchColumn();

            if ($ativos_quantidade <= $ativos_disponiveis) {
                // Buscar ativos disponíveis na sequência
                $sql = "SELECT ID FROM chromebooks WHERE ID NOT IN (
                            SELECT ativo_id FROM reservas 
                            WHERE data_reserva = :data_reserva
                            AND (
                                (hora_retirada < :hora_devolucao AND hora_devolucao > :hora_retirada)
                            )
                        ) LIMIT :quantidade";
                $stmt = $conn_gestao->prepare($sql);
                $stmt->bindParam(':data_reserva', $data_reserva);
                $stmt->bindParam(':hora_retirada', $hora_retirada);
                $stmt->bindParam(':hora_devolucao', $hora_devolucao);
                $stmt->bindParam(':quantidade', $ativos_quantidade, PDO::PARAM_INT);
                $stmt->execute();
                $ativos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($ativos as $ativo_id) {
                    $sql = "INSERT INTO reservas (ativo_id, email_professor, andar_id, sala_id, data_reserva, hora_retirada, hora_devolucao) 
                            VALUES (:ativo_id, :email_professor, :andar_id, :sala_id, :data_reserva, :hora_retirada, :hora_devolucao)";
                    $stmt = $conn_gestao->prepare($sql);
                    $stmt->execute([
                        'ativo_id' => $ativo_id,
                        'email_professor' => $email_professor,
                        'andar_id' => $andar_id,
                        'sala_id' => $sala_id,
                        'data_reserva' => $data_reserva,
                        'hora_retirada' => $hora_retirada,
                        'hora_devolucao' => $hora_devolucao
                    ]);
                }
                $reserva_success = true;
            } else {
                $reserva_error = true;
                $error_message = 'Quantidade indisponível para a data selecionada. Disponível: ' . $ativos_disponiveis . ' ativos.';
            }
        } else {
            echo "Erro: Andar ou Sala inválido.";
        }
    }
}
?>

<?php if ($reserva_success): ?>
<script>
    showModal(true, <?= $ativos_quantidade ?>, '<?= $data_reserva ?>', '<?= $hora_retirada ?>', '<?= $hora_devolucao ?>');
</script>
<?php elseif ($reserva_error): ?>
<script>
    showModal(false, 0, '', '', '', '<?= $error_message ?>');
</script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reserva de Chromebook</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 100%; /* Para tornar o logo responsivo */
            height: auto; /* Para manter a proporção */
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Definir a data atual como padrão
            var now = new Date();
            document.getElementById('data_reserva').valueAsDate = now;

            // Desativar datas passadas
            var today = now.toISOString().split('T')[0];
            document.getElementById('data_reserva').setAttribute('min', today);

            // Gerar opções de horas com intervalos de 30 minutos entre 07:00 e 18:00
            function generateTimeOptions(selectElement) {
                for (let h = 7; h <= 17; h++) {
                    for (let m = 0; m < 60; m += 30) {
                        let hour = h < 10 ? '0' + h : h;
                        let minute = m < 10 ? '0' + m : m;
                        let option = document.createElement('option');
                        option.value = hour + ':' + minute;
                        option.text = hour + ':' + minute;
                        selectElement.appendChild(option);
                    }
                }
                // Adicionar opção para 18:00
                let option = document.createElement('option');
                option.value = '18:00';
                option.text = '18:00';
                selectElement.appendChild(option);
            }

            generateTimeOptions(document.getElementById('hora_retirada'));
            generateTimeOptions(document.getElementById('hora_devolucao'));

            function disablePastTimes() {
                var data_reserva = document.getElementById('data_reserva').value;
                var now = new Date();
                var selectedDate = new Date(data_reserva);

                if (selectedDate.toDateString() === now.toDateString()) {
                    var currentTime = now.getHours() * 60 + now.getMinutes();
                    var hora_retirada = document.getElementById('hora_retirada');
                    var options = hora_retirada.options;

                    for (let i = 0; i < options.length; i++) {
                        let time = options[i].value.split(':');
                        let optionTime = parseInt(time[0]) * 60 + parseInt(time[1]);

                        if (optionTime <= currentTime) {
                            options[i].disabled = true;
                        } else {
                            options[i].disabled = false;
                        }
                    }

                    var hora_devolucao = document.getElementById('hora_devolucao');
                    options = hora_devolucao.options;

                    for (let i = 0; i < options.length; i++) {
                        let time = options[i].value.split(':');
                        let optionTime = parseInt(time[0]) * 60 + parseInt(time[1]);

                        if (optionTime <= currentTime) {
                            options[i].disabled = true;
                        } else {
                            options[i].disabled = false;
                        }
                    }
                } else {
                    var hora_retirada = document.getElementById('hora_retirada');
                    var options = hora_retirada.options;

                    for (let i = 0; i < options.length; i++) {
                        options[i].disabled = false;
                    }

                    var hora_devolucao = document.getElementById('hora_devolucao');
                    options = hora_devolucao.options;

                    for (let i = 0; i < options.length; i++) {
                        options[i].disabled = false;
                    }
                }
            }

            document.getElementById('data_reserva').addEventListener('change', disablePastTimes);
            disablePastTimes(); // Execute na inicialização
        });

        function fetchAvailableAtivos() {
            var data_reserva = document.getElementById('data_reserva').value;
            var hora_retirada = document.getElementById('hora_retirada').value;
            var hora_devolucao = document.getElementById('hora_devolucao').value;
            
            if (data_reserva && hora_retirada && hora_devolucao) {
                fetch('get_quantidade_ativos_disponiveis.php?data_reserva=' + data_reserva + '&hora_retirada=' + hora_retirada + '&hora_devolucao=' + hora_devolucao)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('ativos_disponiveis').innerText = data.quantidade;
                        document.getElementById('ativos_quantidade').max = data.quantidade;
                    });
            }
        }

        function showModal(success, qtdAtivos, data, horaRetirada, horaDevolucao, errorMessage = null) {
            if (success) {
                document.getElementById('modalBody').innerHTML = `
                    <p>Reserva de CB realizada com sucesso</p>
                    <p>Quantidade de ativos reservados: ${qtdAtivos}</p>
                    <p>Data da reserva: ${data}</p>
                    <p>Hora da retirada: ${horaRetirada}</p>
                    <p>Hora da devolução: ${horaDevolucao}</p>
                `;
            } else {
                document.getElementById('modalBody').innerHTML = `
                    <p>${errorMessage}</p>
                `;
            }
            $('#successModal').modal('show');
        }

        function resetForm() {
            document.getElementById('reservaForm').reset();
            document.getElementById('ativos_disponiveis').innerText = '';
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="chamado/abertura_chamado.php" class="btn btn-secondary back-button">Voltar</a>
        <div class="text-center">
            <img src="logo.png" alt="Logo" class="img-fluid my-3">
        </div>
        <h1 class="text-center mt-3">Reserva de Chromebook</h1>
        <form id="reservaForm" method="POST" action="reserva.php" class="mt-4">
            <div class="form-group">
                <label for="data_reserva">Data da Reserva</label>
                <input type="date" id="data_reserva" name="data_reserva" class="form-control" required onchange="fetchAvailableAtivos()">
            </div>
            <div class="form-group">
                <label for="hora_retirada">Hora da Retirada</label>
                <select id="hora_retirada" name="hora_retirada" class="form-control" required onchange="fetchAvailableAtivos()">
                    <option value="">Selecione a hora</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hora_devolucao">Hora da Devolução</label>
                <select id="hora_devolucao" name="hora_devolucao" class="form-control" required onchange="fetchAvailableAtivos()">
                    <option value="">Selecione a hora</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email_username">Email do Professor</label>
                <div class="input-group">
                    <input type="text" id="email_username" name="email_username" class="form-control" required>
                    <div class="input-group-append">
                        <span class="input-group-text">@morumbisul.com.br</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="andar">Andar</label>
                <select name="andar_id" id="andar" class="form-control" required>
                    <option value="">Selecione o andar</option>
                    <?php
                    $stmt = $conn_chamado->query("SELECT * FROM andares");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sala">Sala</label>
                <select name="sala_id" id="sala" class="form-control" required>
                    <option value="">Selecione a sala</option>
                </select>
            </div>
            <div class="form-group">
                <label>Chromebook Disponíveis: <span id="ativos_disponiveis"></span></label>
                <input type="number" name="ativos_quantidade" class="form-control" required min="1" id="ativos_quantidade">
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reserva Realizada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="resetForm()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('andar').addEventListener('change', function() {
            var andarId = this.value;
            var salaSelect = document.getElementById('sala');
            salaSelect.innerHTML = '<option value="">Selecione a sala</option>';
            if (andarId) {
                fetch('get_salas.php?andar_id=' + andarId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sala => {
                            var option = document.createElement('option');
                            option.value = sala.id;
                            option.text = sala.nome;
                            salaSelect.appendChild(option);
                        });
                    });
            }
        });

        <?php if ($reserva_success): ?>
        showModal(true, <?= $ativos_quantidade ?>, '<?= $data_reserva ?>', '<?= $hora_retirada ?>', '<?= $hora_devolucao ?>');
        <?php elseif ($reserva_error): ?>
        showModal(false, 0, '', '', '', '<?= $error_message ?>');
        <?php endif; ?>
    </script>
</body>
</html>
