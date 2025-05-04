<?php
include 'conexao.php';

function gerarAssentos() {
    $letras = ['A'];
    $numeros = range(1, 10);
    $assentos = [];
    foreach ($letras as $l) {
        foreach ($numeros as $n) {
            $assentos[] = $l . $n;
        }
    }
    return $assentos;
}

$assentos = gerarAssentos();
?>
<!DOCTYPE html>
<html>
<head>
    <title>FapFlix - Reservas</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .assentos label { display: inline-block; width: 60px; }
        .ocupado { background-color: #ccc; pointer-events: none; }
    </style>
</head>
<body>
    <h1>FapFlix - Sistema de Cinema</h1>
    <form method="POST" action="fazer_pedido.php">
        <label>Nome:</label><br><input type="text" name="nome" required><br>
        <label>CPF:</label><br><input type="text" name="cpf" maxlength="11" required><br>
        <label>Filme:</label><br>
        <select name="filme" required>
            <option value="Minicraft">Minicraft</option>
            <option value="Jumanji">Jumanji</option>
            <option value="The Chosen">The Chosen</option>
        </select><br>
        <label>Data:</label><br><input type="date" name="data" required><br>
        <label>Horário:</label><br>
        <select name="horario" required>
            <option value="14:00">14:00</option>
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
        </select><br><br>

        <h3>Escolha até 4 assentos:</h3>
        <div class="assentos">
        <?php foreach ($assentos as $a): ?>
            <label><input type="checkbox" name="assentos[]" value="<?= $a ?>"> <?= $a ?></label>
        <?php endforeach; ?>
        </div><br>

        <button type="submit">Reservar</button>
        <a href="ver_pedidos.php"><button type="button">Ver Pedidos</button></a>
    </form>
</body>
</html>