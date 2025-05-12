<?php
require_once 'classes/Database.php';

$db = new Database();
$conn = $db->conectar();

// Adicionar novo filme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && empty($_POST['editar_id'])) {
    $titulo = $_POST['titulo'];

    $stmt = $conn->prepare("INSERT INTO filmes (titulo) VALUES (:titulo)");
    $stmt->execute([':titulo' => $titulo]);
    header("Location: gerenciar_filmes.php");
    exit;
}

// Editar filme existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id']) && isset($_POST['titulo'])) {
    $id = $_POST['editar_id'];
    $titulo = $_POST['titulo'];

    $stmt = $conn->prepare("UPDATE filmes SET titulo = :titulo WHERE id = :id");
    $stmt->execute([':titulo' => $titulo, ':id' => $id]);
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

// Buscar filmes
$stmt = $conn->query("SELECT * FROM filmes");
$filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preparar para editar
$editar_id = $_GET['editar'] ?? null;
$filme_para_editar = null;

if ($editar_id) {
    $stmt = $conn->prepare("SELECT * FROM filmes WHERE id = :id");
    $stmt->execute([':id' => $editar_id]);
    $filme_para_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h2>Gerenciar Filmes</h2>

<form method="post">
    <label>Título:</label><br>
    <input type="text" name="titulo" required value="<?= $filme_para_editar['titulo'] ?? '' ?>"><br><br>

    <?php if ($filme_para_editar): ?>
        <input type="hidden" name="editar_id" value="<?= $filme_para_editar['id'] ?>">
        <button type="submit">Salvar Alterações</button>
        <a href="gerenciar_filmes.php">Cancelar</a>
    <?php else: ?>
        <button type="submit">Adicionar Filme</button>
    <?php endif; ?>
</form>

<h3>Filmes cadastrados</h3>
<ul>
    <?php foreach ($filmes as $filme): ?>
        <li>
            <strong><?= htmlspecialchars($filme['titulo']) ?></strong>
            <a href="?editar=<?= $filme['id'] ?>">Editar</a> |
            <a href="?remover=<?= $filme['id'] ?>" onclick="return confirm('Tem certeza que deseja remover este filme?')">Remover</a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="index.php">Voltar</a>
