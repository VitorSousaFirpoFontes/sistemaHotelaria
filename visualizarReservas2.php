<?php
include "conexao.php";

// Função para obter o número de dias de um mês específico
function obterDiasNoMes($mes, $ano) {
    return cal_days_in_month(CAL_GREGORIAN, $mes + 1, $ano); // Meses começam do 0
}

// Verifica se o formulário foi enviado para atualizar o mês e ano
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : 9; // Default Outubro (mes 9)
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y'); // Ano atual como padrão

$diasNoMes = obterDiasNoMes($mes, $ano);

// Definir mês ajustado (mes + 1)
$mesAjustado = $mes + 1;

// Alterar a consulta SQL para buscar reservas apenas para o mês e ano selecionados
$sql = "SELECT nome_cliente, data_checkin, data_checkout, numero_quarto 
        FROM Reservas 
        WHERE MONTH(data_checkin) = ? AND YEAR(data_checkin) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $mesAjustado, $ano);  // Passando as variáveis diretamente agora
$stmt->execute();
$result = $stmt->get_result();

// Organizar reservas em um array
$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Reservas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" />
</head>
<body class="container mt-5">
    <h1>Controle de Reservas</h1>

    <div class="filtros">
        <form method="GET" action="">
            <label for="mes">Mês:</label>
            <select id="mes" name="mes" onchange="this.form.submit()">
                <option value="0" <?php echo $mes == 0 ? 'selected' : ''; ?>>Janeiro</option>
                <option value="1" <?php echo $mes == 1 ? 'selected' : ''; ?>>Fevereiro</option>
                <option value="2" <?php echo $mes == 2 ? 'selected' : ''; ?>>Março</option>
                <option value="3" <?php echo $mes == 3 ? 'selected' : ''; ?>>Abril</option>
                <option value="4" <?php echo $mes == 4 ? 'selected' : ''; ?>>Maio</option>
                <option value="5" <?php echo $mes == 5 ? 'selected' : ''; ?>>Junho</option>
                <option value="6" <?php echo $mes == 6 ? 'selected' : ''; ?>>Julho</option>
                <option value="7" <?php echo $mes == 7 ? 'selected' : ''; ?>>Agosto</option>
                <option value="8" <?php echo $mes == 8 ? 'selected' : ''; ?>>Setembro</option>
                <option value="9" <?php echo $mes == 9 ? 'selected' : ''; ?>>Outubro</option>
                <option value="10" <?php echo $mes == 10 ? 'selected' : ''; ?>>Novembro</option>
                <option value="11" <?php echo $mes == 11 ? 'selected' : ''; ?>>Dezembro</option>
            </select>

            <label for="ano">Ano:</label>
            <select id="ano" name="ano" onchange="this.form.submit()">
                <?php
                $anoAtual = date('Y');
                for ($i = $anoAtual - 5; $i <= $anoAtual + 5; $i++) {
                    echo "<option value='$i' " . ($i == $ano ? 'selected' : '') . ">$i</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Quarto</th>
                <?php
                for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                    echo "<th>$dia/" . ($mes + 1) . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Gerar a tabela de quartos
            for ($quarto = 1; $quarto <= 30; $quarto++) {
                echo "<tr>";
                echo "<td><strong>Quarto $quarto</strong></td>";

                // Preencher os dias do mês para o quarto
                for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                    $reservado = false;
                    foreach ($reservas as $reserva) {
                        $checkin = new DateTime($reserva['data_checkin']);
                        $checkout = new DateTime($reserva['data_checkout']);
                        if ($reserva['numero_quarto'] == $quarto && $dia >= $checkin->format('d') && $dia < $checkout->format('d')) {
                            $reservado = true;
                            break;
                        }
                    }

                    if ($reservado) {
                        echo "<td style='background-color: #ffd700'>" . $reserva['nome_cliente'] . "</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-primary">Ir para tela inicial</a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
