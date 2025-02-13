<?php
header('Content-Type: application/json');
require 'conexao.php';

try {
    $stmt = $pdo->query("SELECT * FROM estoque ORDER BY data_criacao DESC");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $items]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao carregar itens: ' . $e->getMessage()]);
}
?>