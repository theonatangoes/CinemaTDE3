<?php
include 'conexao.php';

$id = $_POST['id'];

// Buscar o assento do pedido que será removido
$stmt = $conn->prepare("SELECT assento, filme, data, horario FROM pedidos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if ($pedido) {
    // Excluir o pedido
    $stmt = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redireciona para a página de pedidos
    header("Location: ver_pedidos.php");
    exit;
} else {
    echo "Pedido não encontrado.";
}
?>
