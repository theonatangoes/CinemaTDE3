<?php
include "conexao.php";
$id = intval($_GET['id']);

// Buscar o filme
$result = $conn->query("SELECT * FROM filmes WHERE id = $id");
if ($result->num_rows !== 1) {
    die("Filme não encontrado.");
}
$filme = $result->fetch_assoc();

// Atualizar nome
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nome"])) {
    $novo_nome = mysqli_real_escape_string($conn, $_POST["nome"]);
    $conn->query("UPDATE filmes SET nome = '$novo_nome' WHERE id = $id");
    header("Location: admin_filmes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Filme</title>
</head>
<body>
  <h1>Editar Filme</h1>
  <form method="POST">
    <label>Novo nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($filme['nome']) ?>" required>
    <button type="submit">Salvar</button>
  </form>
  <a href="admin_filmes.php">⬅ Voltar</a>
</body>
</html>
