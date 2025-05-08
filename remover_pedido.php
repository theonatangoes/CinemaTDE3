<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';

$db = new Database();
$conn = $db->conectar();
$pedido = new Pedido($conn);

if (isset($_GET['id'])) {
    $pedido->removerPorId($_GET['id']);
}
header("Location: ver_pedido.php");
