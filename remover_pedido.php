<?php
include 'conexao.php';
$id = $_POST['id'];
$conn->query("DELETE FROM pedidos WHERE id=$id");
header("Location: ver_pedidos.php");
?>