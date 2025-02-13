<?php
include 'conexao.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : 2;

$sql = "SELECT 
            DAY(data) as dia,
            SUM(valor_total) as total
        FROM RelatoriosFinanceiros
        WHERE MONTH(data) = ?
        GROUP BY DAY(data)
        ORDER BY dia";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mes);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$valores = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = 'Dia ' . $row['dia'];
    $valores[] = $row['total'];
}

$conn->close();

echo json_encode([
    'labels' => $labels,
    'valores' => $valores
]);
?>