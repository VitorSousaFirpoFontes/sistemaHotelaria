<?php
// listar_reservas.php

include "conexao.php";

// Consultar todas as reservas
$sql = "SELECT nome_cliente, data_checkin, data_checkout, numero_quarto FROM Reservas";
$result = $conn->query($sql);

$reservas = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}

// Retornar os dados em formato JSON
echo json_encode($reservas);

$conn->close();
?>
