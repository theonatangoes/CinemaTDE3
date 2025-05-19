<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';

$db = new Database();
$conn = $db->conectar();
$pedidoObj = new Pedido($conn);

// Pega o ID do pedido pela URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do pedido não informado.");
}

// Busca os dados atuais do pedido
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = :id");
$stmt->execute([':id' => $id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Atualiza os dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $forma_pagamento = $_POST['forma_pagamento'];

    if (!preg_match('/^\d{11}$/', $cpf)) {
        die("CPF inválido. Digite exatamente 11 números.");
    }

    $atualizado = $pedidoObj->editarPorId($id, $nome, $cpf, $forma_pagamento);

    if ($atualizado) {
        echo "Pedido atualizado com sucesso!";
        echo "<br><a href='ver_pedido.php'>Voltar</a>";
        exit;
    } else {
        echo "Erro ao atualizar pedido.";
    }
}
?>

<h2>Editar Pedido</h2>
<form method="post">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($pedido['nome_cliente']) ?>" required><br>
    CPF: <input type="text" name="cpf" value="<?= htmlspecialchars($pedido['cpf']) ?>" required><br>
    Forma de Pagamento:
    <select name="forma_pagamento" required>
        <?php foreach (["PIX", "Cartão de Crédito", "Cartão de Débito", "Dinheiro"] as $opcao): ?>
            <option value="<?= $opcao ?>" <?= $pedido['forma_pagamento'] == $opcao ? 'selected' : '' ?>><?= $opcao ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <button type="submit">Salvar Alterações</button>
</form>
<a href="ver_pedido.php">Cancelar</a>
