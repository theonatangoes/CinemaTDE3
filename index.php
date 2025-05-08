<?php
require_once 'classes/Database.php';
require_once 'classes/Filme.php';

$db = new Database();
$conn = $db->conectar();
$filme = new Filme($conn);
$filmes = $filme->listar();
$hoje = date('Y-m-d');
$horarios = ['14:00', '18:00', '20:00'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FAP Flix</title>
    
</head>
<body>
<h1>FAP Flix</h1>
<h2>Fazer Pedido</h2>
<form action="fazer_pedido.php" method="post">
    <label>Nome:</label><input type="text" name="nome" required><br>
    <label>CPF:</label><input type="text" name="cpf" maxlength="11" required><br>
    <label>Filme:</label>
    <select name="filme_id" required>
        <?php foreach ($filmes as $f): ?>
            <option value="<?= $f['id'] ?>"><?= $f['titulo'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <label>Data:</label><input type="date" name="data" min="<?= $hoje ?>" required><br>
    <label>Horário:</label>
    <select name="horario" required>
        <?php foreach ($horarios as $h): ?>
            <option value="<?= $h ?>"><?= $h ?></option>
        <?php endforeach; ?>
    </select><br>
    <label>Assentos (máx 4):</label><br>
    <?php for ($i = 1; $i <= 10; $i++): ?>
        <input type="checkbox" name="assentos[]" value="A<?= $i ?>">A<?= $i ?>
    <?php endfor; ?><br>
    <button type="submit">Confirmar Pedido</button>
</form>

<br><a href="ver_pedido.php"><button>Ver Pedidos</button></a>
<br><a href="gerenciar_filmes.php"><button>Gerenciar Filmes</button></a>
</body>
</html>
