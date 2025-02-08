<?php

$server="localhost";
$user="root";
$pass="1234";
$bd="hotel";
// Criar conexão
$conn= mysqli_connect($server, $user, $pass, $bd);


// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
