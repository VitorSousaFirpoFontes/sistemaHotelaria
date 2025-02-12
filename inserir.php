<?php 
include "conexao.php";

// Função para calcular o valor total
function calcularValorTotal($valor_diaria, $data_checkin, $data_checkout) {
    $checkin_timestamp = strtotime($data_checkin);
    $checkout_timestamp = strtotime($data_checkout);
    $diferenca_dias = ($checkout_timestamp - $checkin_timestamp) / 86400; // Convertendo segundos para dias
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
