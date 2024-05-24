<?php
include 'db_connect.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

function fetch_reservas($date, $conn_gestao, $conn_chamado) {
    $sql = "SELECT reservas.*, chromebooks.Nome AS ativo_nome, a.nome AS andar_nome, s.nome AS sala_nome 
            FROM reservas
            JOIN chromebooks ON reservas.ativo_id = chromebooks.ID
            JOIN chamado.salas AS s ON reservas.sala_id = s.id
            JOIN chamado.andares AS a ON s.id_andar = a.id
            WHERE reservas.data_reserva = :date
            ORDER BY email_professor, hora_retirada";
    $stmt = $conn_gestao->prepare($sql);
    $stmt->execute(['date' => $date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$reservas = fetch_reservas($date, $conn_gestao, $conn_chamado);

// Agrupar reservas por professor e data
$reservasAgrupadas = [];
if ($reservas) {
    foreach ($reservas as $reserva) {
        $key = $reserva['email_professor'] . '_' . $reserva['data_reserva'];
        if (!isset($reservasAgrupadas[$key])) {
            $reservasAgrupadas[$key] = [
                'email_professor' => $reserva['email_professor'],
                'data_reserva' => $reserva['data_reserva'],
                'andar_nome' => $reserva['andar_nome'],
                'sala_nome' => $reserva['sala_nome'],
                'hora_retirada' => $reserva['hora_retirada'],
                'hora_devolucao' => $reserva['hora_devolucao'],
                'ativos' => [],
                'ids' => [],
                'status' => isset($reserva['status']) ? $reserva['status'] : 'pendente' // Adiciona um valor padrão para status
            ];
        }
        $reservasAgrupadas[$key]['ativos'][] = $reserva['ativo_nome'];
        $reservasAgrupadas[$key]['ids'][] = $reserva['id'];
    }
}

if (isset($_GET['ajax'])) {
    echo json_encode($reservasAgrupadas);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reservas do Dia</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-custom {
            background-color: #f8f9fa;
        }
        .card-devolvido {
            background-color: #d1ecf1;
        }
        .card-retirado {
            background-color: #d4edda;
        }
    </style>
    <script>
        let previousReservas = [];

        function playNotificationSound() {
            const audio = new Audio('notification_sound.mp3');
            audio.play();
        }

        function cancelarReserva(ids) {
            fetch('cancelar_reserva.php?ids=' + ids.join(','))
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        ids.forEach(id => document.getElementById('card-' + id).remove());
                    } else {
                        alert('Erro ao cancelar a reserva.');
                    }
                });
        }

        function fetchReservas() {
            const date = document.getElementById('date').value;
            fetch(`reservas_dia.php?ajax=1&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('reservas-container');

                    // Comparar novas reservas com as anteriores
                    const newReservas = Object.keys(data).filter(key => !previousReservas.includes(key));
                    if (newReservas.length > 0) {
                        playNotificationSound();
                    }

                    previousReservas = Object.keys(data);

                    container.innerHTML = '';

                    Object.keys(data).forEach(key => {
                        const reserva = data[key];
                        let cardClass = 'card-custom';
                        if (reserva.status === 'retirado') {
                            cardClass = 'card-retirado';
                        } else if (reserva.status === 'devolvido') {
                            cardClass = 'card-devolvido';
                        }
                        const card = document.createElement('div');
                        card.className = 'col-md-4';
                        card.id = 'card-' + reserva.ids.join('-');
                        card.setAttribute('data-ativos', reserva.ativos.join(','));
                        card.setAttribute('data-email', reserva.email_professor);
                        card.setAttribute('data-andar', reserva.andar_nome);
                        card.setAttribute('data-sala', reserva.sala_nome);
                        card.setAttribute('data-reserva', reserva.data_reserva);
                        card.setAttribute('data-hora_retirada', reserva.hora_retirada);
                        card.setAttribute('data-hora_devolucao', reserva.hora_devolucao);
                        card.innerHTML = `
                            <div class="card mb-4 ${cardClass}">
                                <div class="card-body">
                                    <h5 class="card-title">Professor: ${reserva.email_professor}</h5>
                                    <p class="card-text">
                                        Andar: ${reserva.andar_nome}<br>
                                        Sala: ${reserva.sala_nome}<br>
                                        Data: ${reserva.data_reserva}<br>
                                        Retirada: ${reserva.hora_retirada}<br>
                                        Devolução: ${reserva.hora_devolucao}<br>
                                        Ativos: ${reserva.ativos.join(', ')}<br>
                                    </p>
                                    <button class="btn btn-danger" onclick='cancelarReserva(${JSON.stringify(reserva.ids)})'>Cancelar Reserva</button>
                                    ${reserva.status === 'retirado' ? '<button class="btn btn-primary" onclick="devolverTotal(' + JSON.stringify(reserva.ids) + ')">Devolução Total</button>' : '<button class="btn btn-success" onclick="marcarRetirado(' + JSON.stringify(reserva.ids) + ')">Retirar</button>'}
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                    });
                });
        }

        function marcarRetirado(ids) {
            fetch('marcar_retirado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        ids.forEach(id => {
                            const card = document.getElementById('card-' + id);
                            if (card) {
                                card.classList.remove('card-custom');
                                card.classList.add('card-retirado');
                                const button = card.querySelector('button.btn-success');
                                if (button) {
                                    button.remove();
                                }
                                const cancelButton = card.querySelector('button.btn-danger');
                                if (cancelButton) {
                                    cancelButton.remove();
                                }
                                const newButton = document.createElement('button');
                                newButton.className = 'btn btn-primary';
                                newButton.textContent = 'Devolução Total';
                                newButton.setAttribute('onclick', 'devolverTotal(' + JSON.stringify(ids) + ')');
                                card.querySelector('.card-body').appendChild(newButton);
                            }
                        });
                    } else {
                        alert('Erro ao marcar como retirado.');
                    }
                });
        }

        function devolverTotal(ids) {
            fetch('devolver_total.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        ids.forEach(id => {
                            const card = document.getElementById('card-' + id);
                            if (card) {
                                card.remove();
                            }
                        });
                    } else {
                        alert('Erro ao marcar como devolvido.');
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchReservas();
            setInterval(fetchReservas, 5000);
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Reservas do Dia</h1>
        <form method="GET" class="mb-4" onsubmit="return false;">
            <div class="form-group">
                <label for="date">Selecionar Data</label>
                <input type="date" id="date" name="date" class="form-control" value="<?= $date ?>" onchange="fetchReservas()">
            </div>
        </form>
        <div class="row" id="reservas-container">
            <?php if (!empty($reservasAgrupadas)): ?>
                <?php foreach ($reservasAgrupadas as $key => $reserva): ?>
                <div class="col-md-4" id="card-<?= htmlspecialchars(implode('-', $reserva['ids'])) ?>" data-ativos="<?= htmlspecialchars(implode(',', $reserva['ativos'])) ?>" data-email="<?= htmlspecialchars($reserva['email_professor']) ?>" data-andar="<?= htmlspecialchars($reserva['andar_nome']) ?>" data-sala="<?= htmlspecialchars($reserva['sala_nome']) ?>" data-reserva="<?= htmlspecialchars($reserva['data_reserva']) ?>" data-hora_retirada="<?= htmlspecialchars($reserva['hora_retirada']) ?>" data-hora_devolucao="<?= htmlspecialchars($reserva['hora_devolucao']) ?>">
                    <div class="card mb-4 <?= $reserva['status'] === 'devolvido' ? 'card-devolvido' : ($reserva['status'] === 'retirado' ? 'card-retirado' : 'card-custom') ?>">
                        <div class="card-body">
                            <h5 class="card-title">Professor: <?= htmlspecialchars($reserva['email_professor']) ?></h5>
                            <p class="card-text">
                                Andar: <?= htmlspecialchars($reserva['andar_nome']) ?><br>
                                Sala: <?= htmlspecialchars($reserva['sala_nome']) ?><br>
                                Data: <?= htmlspecialchars($reserva['data_reserva']) ?><br>
                                Retirada: <?= htmlspecialchars($reserva['hora_retirada']) ?><br>
                                Devolução: <?= htmlspecialchars($reserva['hora_devolucao']) ?><br>
                                Ativos: <?= htmlspecialchars(implode(', ', $reserva['ativos'])) ?><br>
                            </p>
                            <button class="btn btn-danger" onclick="cancelarReserva(<?= htmlspecialchars(json_encode($reserva['ids'])) ?>)">Cancelar Reserva</button>
                            <?= $reserva['status'] === 'retirado' ? '<button class="btn btn-primary" onclick="devolverTotal(' . htmlspecialchars(json_encode($reserva['ids'])) . ')">Devolução Total</button>' : '<button class="btn btn-success" onclick="marcarRetirado(' . htmlspecialchars(json_encode($reserva['ids'])) . ')">Retirar</button>' ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma reserva encontrada para a data selecionada.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
