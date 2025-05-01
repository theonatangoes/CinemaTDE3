<?php
$host = "localhost";
$user = "root";
$senha = "";
$banco = "cinema";

$conn = new mysqli($host, $user, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>