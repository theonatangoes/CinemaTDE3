<?php
include 'conexao.php';
$pedidos = $conn->query("SELECT * FROM pedidos ORDER BY data DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Pedidos</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background-color: #eee; }
        button:disabled { background-color: #ddd; cursor: not-allowed; }
    </style>
</head>
<body>
    <h1>Pedidos de Reservas</h1>
    <table>
        <tr>
            <th>Nome</th><th>CPF</th><th>Filme</th><th>Data</th><th>Horário</th><th>Assento</th><th>Ação</th>
        </tr>
        <?php while ($row = $pedidos->fetch_assoc()): ?>
        <tr>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['cpf'] ?></td>
            <td><?= $row['filme'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['data'])) ?></td>
            <td><?= $row['horario'] ?></td>
            <td><?= $row['assento'] ?></td>
            <td>
                <form method="POST" action="remover_pedido.php">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit">Remover</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Botão Voltar -->
    <br>
    <a href="index.php"><button>Voltar</button></a>
</body>
</html>
