<?php
include "conexao.php";

$sql = "SELECT * FROM Reservas";
$result = $conn->query($sql);

$reservas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}

echo json_encode($reservas);

$conn->close();
?>
