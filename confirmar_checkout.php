<?php
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cliente = $_POST['nome_cliente'];
    $numero_quarto = $_POST['numero_quarto'];

    // Atualizar a reserva no banco de dados (marcar como checkout realizado)
    $sql = "UPDATE Reservas SET data_checkout = NOW() WHERE nome_cliente = ? AND numero_quarto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome_cliente, $numero_quarto);
    $stmt->execute();
    $conn->close();

    echo "Checkout confirmado!";
}
?>
