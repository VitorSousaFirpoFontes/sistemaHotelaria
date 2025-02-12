<?php
include 'conexao.php';

$sql = "SELECT SUM(lucro_total) AS total_lucro FROM RelatoriosFinanceiros";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo number_format($row['total_lucro'], 2, ',', '.');
} else {
    echo "0,00";
}

$conn->close();
?>
