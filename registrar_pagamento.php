<?php
include "conexao.php";

// Ativar relatório de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se todos os parâmetros foram recebidos
if (!isset($_POST['id'], $_POST['valor_diaria'], $_POST['valor_total'], $_POST['data_pagamento'])) {
    die("Parâmetros incompletos!");
}

// Atribuições CORRETAS com nomes compatíveis
$id_reserva = $_POST['id']; // Nome do parâmetro recebido via POST
$valor_diaria = str_replace(['.', ','], ['', '.'], $_POST['valor_diaria']); // Formatação brasileira
$valor_total = str_replace(['.', ','], ['', '.'], $_POST['valor_total']);
$data_pagamento = $_POST['data_pagamento'];

// Verifica se já existe registro
$sql_check = "SELECT id_reserva FROM relatoriosfinanceiros WHERE id_reserva = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_reserva);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("Checkout já registrado para esta reserva!");
}

// Corrigindo o nome da coluna para valor_diario (igual ao banco)
$sql_insert = "INSERT INTO relatoriosfinanceiros (id_reserva, valor_diario, valor_total, data_pagamento) 
               VALUES (?, ?, ?, ?)";
               
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("idds", $id_reserva, $valor_diaria, $valor_total, $data_pagamento);

if ($stmt_insert->execute()) {
    echo "Pagamento registrado!";
} else {
    echo "Erro: " . $stmt_insert->error;
}

$conn->close();
?>