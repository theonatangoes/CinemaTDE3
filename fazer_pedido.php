<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';
require_once 'classes/Filme.php';

$db = new Database();
$conn = $db->conectar();
$pedido = new Pedido($conn);
$filmeObj = new Filme($conn);
$filmes = $filmeObj->listar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $filme_id = $_POST['filme_id'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $assentos = $_POST['assentos'] ?? [];

    if (strtotime($data) < strtotime(date('Y-m-d'))) {
        die('Erro: Data inválida.');
    }

    if ($pedido->fazer($nome, $cpf, $filme_id, $data, $horario, $assentos)) {
        echo "Pedido realizado com sucesso!";
    } else {
        echo "Erro ao realizar pedido (limite de cadeiras pode ter sido excedido).";
    }
}
?>

<h2>FAP Flix</h2>
<h3>Fazer Pedido</h3>

<form method="post">
    Nome: <input type="text" name="nome" required><br>
    CPF: <input type="text" name="cpf" required><br>
    Filme:
    <select name="filme_id" required>
        <?php foreach ($filmes as $filme): ?>
            <option value="<?= $filme['id'] ?>"><?= $filme['titulo'] ?></option>
        <?php endforeach; ?>
    </select><br>
    Data: <input type="date" name="data" required><br>
    Horário:
    <select name="horario" required>
        <option value="14:00">14:00</option>
        <option value="18:00">18:00</option>
        <option value="20:00">20:00</option>
    </select><br>
    Assentos:<br>
    <?php foreach (range(1, 10) as $i): ?>
        <label><input type="checkbox" name="assentos[]" value="A<?= $i ?>">A<?= $i ?></label>
    <?php endforeach; ?>
    <br><br>
    <button type="submit">Confirmar Pedido</button>
</form>

<br>
<a href="ver_pedido.php"><button>Ver Pedidos</button></a><br>
<a href="gerenciar_filmes.php"><button>Gerenciar Filmes</button></a>
