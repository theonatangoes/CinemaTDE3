<?php
include "conexao.php";
$id = $_GET['id'];
$p = $conn->query("SELECT * FROM pedidos WHERE id=$id")->fetch_assoc();
$f = $conn->query("SELECT * FROM filmes");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$conn->query("UPDATE pedidos SET nome_cliente='{$_POST['nome_cliente']}', filme_id='{$_POST['filme']}',
 data='{$_POST['data']}', horario='{$_POST['horario']}', assento='{$_POST['assento']}' WHERE id=$id");
echo "Atualizado! <a href='relatorio.php'>Voltar</a>"; exit;
}
?>
<form method="POST">
Nome: <input name="nome_cliente" value="<?= $p['nome_cliente'] ?>"><br>
Filme: <select name="filme"><?php while ($row = $f->fetch_assoc()) {
$s = $row['id']==$p['filme_id']?"selected":"";
echo "<option value='{$row['id']}' $s>{$row['nome']}</option>"; } ?></select><br>
Data: <input type="date" name="data" value="<?= $p['data'] ?>"><br>
Hora: <input type="time" name="horario" value="<?= $p['horario'] ?>"><br>
Assento: <input name="assento" value="<?= $p['assento'] ?>"><br>
<button type="submit">Salvar</button></form>
