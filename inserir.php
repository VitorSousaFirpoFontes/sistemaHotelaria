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

<?php 
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cliente = $_POST['nome_cliente'] ?? '';
    $data_checkin = $_POST['data_checkin'] ?? '';
    $data_checkout = $_POST['data_checkout'] ?? '';
    $numero_quartos = $_POST['numero_quartos'] ?? '';
    $tipo_quarto = $_POST['tipo_quarto'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';

    // Prepara a consulta SQL para inserir os dados
    $sql = "INSERT INTO Reservas (nome_cliente, data_checkin, data_checkout, numero_quartos, tipo_quarto, observacoes) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Usa prepared statement para evitar SQL injection
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Usa bind_param para associar os parâmetros
    $stmt->bind_param("ssssss", $nome_cliente, $data_checkin, $data_checkout, $numero_quartos, $tipo_quarto, $observacoes);

    // Executa a consulta e verifica o resultado
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Reserva de $nome_cliente cadastrada com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar: " . $stmt->error . "</div>";
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>

<a href='index.php' class='btn btn-primary'>Ir para tela inicial</a>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
