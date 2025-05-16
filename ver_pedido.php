<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';

$db = new Database();
$conn = $db->conectar();
$pedido = new Pedido($conn);

// Agora lista todos os pedidos automaticamente
$pedidos = $pedido->listarTodos();
?>

<h2>Pedidos</h2>
<?php if (!empty($pedidos)): ?>
    <ul>
        <?php foreach ($pedidos as $p): ?>
            <li>
                <strong>Nome:</strong> <?= htmlspecialchars($p['nome_cliente']) ?> |
                <strong>CPF:</strong> <?= htmlspecialchars($p['cpf']) ?> |
                <strong>Filme:</strong> <?= htmlspecialchars($p['titulo']) ?> |
                <strong>Data:</strong> <?= date('d-m-Y', strtotime($p['data'])) ?> |
                <strong>Horário:</strong> <?= htmlspecialchars($p['horario']) ?> |
                <strong>Assento:</strong> <?= htmlspecialchars($p['assento']) ?> |
                <strong>Forma de Pagamento:</strong> <?= htmlspecialchars($p['forma_pagamento']) ?>
                <a href="remover_pedido.php?id=<?= $p['id'] ?>">Remover</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>
<a href="index.php">Voltar</a>
