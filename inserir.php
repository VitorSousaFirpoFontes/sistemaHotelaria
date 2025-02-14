<?php 
include "conexao.php";

// Função para calcular o valor total
include "conexao.php";

// Função para calcular o valor total corrigida
function calcularValorTotal($valor_diaria, $data_checkin, $data_checkout) {
    try {
        $checkin = new DateTime($data_checkin);
        $checkout = new DateTime($data_checkout);
    } catch (Exception $e) {
        return 0; // Retorna 0 em caso de datas inválidas
    }

    // Define o horário para 00:00:00 para considerar apenas a diferença de dias
    $checkin->setTime(0, 0, 0);
    $checkout->setTime(0, 0, 0);

    // Verifica se a data de checkout é anterior ou igual ao checkin
    if ($checkout <= $checkin) {
        return 0;
    }

    // Calcula a diferença de dias
    $interval = $checkin->diff($checkout);
    $diferenca_dias = $interval->days;

    return $valor_diaria * $diferenca_dias;
}

// Inserção
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cliente = $_POST['nome_cliente'] ?? '';
    $data_checkin = $_POST['data_checkin'] ?? '';
    $data_checkout = $_POST['data_checkout'] ?? '';
    $numero_quarto = $_POST['numero_quarto'] ?? '';
    $tipo_quarto = $_POST['tipo_quarto'] ?? '';
    $valor_diaria = $_POST['valor_diaria'] ?? ''; 
    $observacoes = $_POST['observacoes'] ?? '';

    // Calculando o valor total
    $valor_total = calcularValorTotal($valor_diaria, $data_checkin, $data_checkout);

    // Valida se o valor total é positivo
    if ($valor_total <= 0) {
        echo "<div class='alert alert-danger text-center' role='alert'>
                Datas inválidas. O checkout deve ser após o checkin.
              </div>";
        exit;
    }

    // Prepara a consulta SQL para inserir os dados
    $sql = "INSERT INTO Reservas (nome_cliente, data_checkin, data_checkout, numero_quarto, tipo_quarto, valor_diaria, valor_total, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Usa prepared statement para evitar SQL injection
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Usa bind_param para associar os parâmetros
    $stmt->bind_param("ssssssss", $nome_cliente, $data_checkin, $data_checkout, $numero_quarto, $tipo_quarto, $valor_diaria, $valor_total, $observacoes);

    // Executa a consulta e verifica o resultado
    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center' role='alert'>
                Reserva de $nome_cliente cadastrada com sucesso! 
              </div>";
    } else {
        echo "<div class='alert alert-danger text-center' role='alert'>
                Erro ao cadastrar: " . $stmt->error . "
              </div>";
    }

    // Fecha a declaração e a conexão
    $stmt->close();
}

// Restante do código (leitura, HTML, scripts) permanece o mesmo
// Leitura
$sql_leitura = "SELECT * FROM Reservas";
$resultado = $conn->query($sql_leitura);

?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Reserva de Hotel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" />
</head>
<body class="container mt-5">



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>
