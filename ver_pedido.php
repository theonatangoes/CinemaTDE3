<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';

$db = new Database();
$conn = $db->conectar();
$pedido = new Pedido($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'];
    $pedidos = $pedido->listarPorCPF($cpf);
}
?>

<form method="post">
    <label>CPF:</label>
    <input type="text" name="cpf" required>
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($pedidos)): ?>
    <h2>Pedidos</h2>
    <ul>
        <?php foreach ($pedidos as $p): ?>
            <li><?= $p['titulo'] ?> - <?= $p['data'] ?> - <?= $p['horario'] ?> - <?= $p['assento'] ?>
                <a href="remover_pedido.php?id=<?= $p['id'] ?>">Remover</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<a href="index.php">Voltar</a>
