<?php
include 'conexao.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : null;
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : null;

$sql = "SELECT data_pagamento, CONCAT('Pagamento de reserva') AS descricao, valor_total 
        FROM RelatoriosFinanceiros
        WHERE 1=1";

if ($mes && $mes > 0) {
    $sql .= " AND MONTH(data_pagamento) = " . $mes;
}
if ($ano && $ano > 0) {
    $sql .= " AND YEAR(data_pagamento) = " . $ano;
}

$sql .= " ORDER BY data_pagamento DESC LIMIT 10";

$result = $conn->query($sql);

$transacoes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $transacoes[] = [
            'data' => date('d/m/Y', strtotime($row['data_pagamento'])),
            'descricao' => $row['descricao'],
            'valor' => 'R$ ' . number_format($row['valor_total'], 2, ',', '.')
        ];
    }
}

$conn->close();
echo json_encode($transacoes);
?>
