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
    $forma_pagamento = $_POST['forma_pagamento'];

    // Validação de CPF: apenas números e 11 dígitos
    if (!preg_match('/^\d{11}$/', $cpf)) {
        die("Erro: CPF inválido. Digite exatamente 11 números.");
    }

    $filme_id = $_POST['filme_id'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $assentos = $_POST['assentos'] ?? [];

    // Verificação de pelo menos um assento
    if (empty($assentos)) {
        die("Erro: Selecione pelo menos um assento.");
    }

    if (strtotime($data) < strtotime(date('Y-m-d'))) {
        die('Erro: Data inválida.');
    }

    if ($pedido->fazer($nome, $cpf, $filme_id, $data, $horario, $assentos, $forma_pagamento)) {
        echo "Pedido realizado com sucesso!";
    } else {
        echo "Erro ao realizar pedido (limite de cadeiras pode ter sido excedido).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FAP Flix</title>
    <style>
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 110px;
            width: auto;
        }
        h1 {
            margin: 0;
            font-size: 2em;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="FapFlix.png" alt="Logo FAP Flix">
</div>
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
    Forma de Pagamento:
    <select name="forma_pagamento" required>
        <option value="PIX">PIX</option>
        <option value="Cartão de Crédito">Cartão de Crédito</option>
        <option value="Cartão de Débito">Cartão de Débito</option>
        <option value="Dinheiro">Dinheiro</option>
    </select><br><br>
    <button type="submit">Confirmar Pedido</button>
</form>

<br>
<a href="ver_pedido.php"><button>Ver Pedidos</button></a><br>
<a href="gerenciar_filmes.php"><button>Gerenciar Filmes</button></a>
</body>
</html>
