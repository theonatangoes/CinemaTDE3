<?php
require_once 'classes/Database.php';

$db = new Database();
$conn = $db->conectar();

// Adicionar filme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];

    $stmt = $conn->prepare("INSERT INTO filmes (titulo) VALUES (:titulo)");
    $stmt->execute([':titulo' => $titulo]);
    header("Location: gerenciar_filmes.php");
    exit;
}

// Remover filme
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];

    $stmt = $conn->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: gerenciar_filmes.php");
    exit;
}

// Listar filmes
$stmt = $conn->query("SELECT * FROM filmes");
$filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gerenciar Filmes</h2>

<form method="post">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <button type="submit">Adicionar Filme</button>
</form>

<h3>Filmes cadastrados</h3>
<ul>
    <?php foreach ($filmes as $filme): ?>
        <li>
            <strong><?= htmlspecialchars($filme['titulo']) ?></strong>
            <a href="?remover=<?= $filme['id'] ?>" onclick="return confirm('Tem certeza que deseja remover este filme?')">Remover</a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="index.php">Voltar</a>
