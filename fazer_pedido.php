<?php
include 'conexao.php';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$filme = $_POST['filme'];
$data = $_POST['data'];
$horario = $_POST['horario'];
$assentos = $_POST['assentos'];

$query = "SELECT COUNT(*) as total FROM pedidos WHERE cpf=? AND filme=? AND data=? AND horario=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $cpf, $filme, $data, $horario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['total'] + count($assentos) > 4) {
    die("Erro: Limite de 4 assentos por CPF excedido.");
}

foreach ($assentos as $assento) {
    $check = $conn->prepare("SELECT * FROM pedidos WHERE filme=? AND data=? AND horario=? AND assento=?");
    $check->bind_param("ssss", $filme, $data, $horario, $assento);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO pedidos (nome, cpf, filme, data, horario, assento) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssss", $nome, $cpf, $filme, $data, $horario, $assento);
        $insert->execute();
    }
}
header("Location: ver_pedidos.php");
?>