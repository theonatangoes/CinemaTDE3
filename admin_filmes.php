<?php
include "conexao.php";

// Inserir filme
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["novo_filme"])) {
    $nome = mysqli_real_escape_string($conn, $_POST["novo_filme"]);
    if (!empty($nome)) {
        $conn->query("INSERT INTO filmes (nome) VALUES ('$nome')");
    }
    header("Location: admin_filmes.php");
    exit;
}

// Deletar filme
if (isset($_GET["excluir"])) {
    $id = intval($_GET["excluir"]);
    $conn->query("DELETE FROM filmes WHERE id = $id");
    header("Location: admin_filmes.php");
    exit;
}

// Listar filmes
$filmes = $conn->query("SELECT * FROM filmes");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Admin - Gerenciar Filmes</title>
</head>
<body>
  <h1>🎬 Administração de Filmes</h1>

  <form method="POST" action="admin_filmes.php">
    <label>Adicionar novo filme:</label>
    <input type="text" name="novo_filme" required>
    <button type="submit">Adicionar</button>
  </form>

  <h2>Filmes cadastrados:</h2>
  <ul>
    <?php while ($f = $filmes->fetch_assoc()) { ?>
      <li>
        <?= htmlspecialchars($f['nome']) ?>
        [<a href="editar_filme.php?id=<?= $f['id'] ?>">Editar</a>] 
        [<a href="admin_filmes.php?excluir=<?= $f['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este filme?')">Excluir</a>]
      </li>
    <?php } ?>
  </ul>

  <a href="index.php">⬅ Voltar ao sistema</a>
</body>
</html>
