<?php
include "conexao.php";

if (isset($_GET['error'])) {
  ?>
  <script>
      alert('<?php echo $_GET['error']; ?>');
  </script>
  <?php
}

$filme = $_GET['filme'] ?? '';
$data = $_GET['data'] ?? '';
$horario = $_GET['horario'] ?? '';

$sql = "SELECT assento FROM pedidos WHERE filme_id = '$filme' AND data = '$data' AND horario = '$horario'";
$result = $conn->query($sql);

$ocupados = [];
while ($row = $result->fetch_assoc()) {
    $ocupados[] = $row['assento'];
}

$filmes = $conn->query("SELECT * FROM filmes");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cinema</title>
</head>
<body>
<h1>FapFlix</h1>
<a href="admin_filmes.php">
  <button type="button">🎬 Gerenciar Filmes</button>
</a>
<form method="GET">
  Filme:
  <select name="filme">
    <?php while ($f = $filmes->fetch_assoc()) {
      $s = ($f['id']==$filme) ? "selected" : "";
      echo "<option value='{$f['id']}' $s>{$f['nome']}</option>";
    } ?>
  </select>
  Data:
  <input type="date" name="data" value="<?= $data ?>" min="<?= date('Y-m-d') ?>" required>
  Horário:
  <select name="horario">
    <?php foreach(['14:10:00','16:00:00','18:00:00','20:00:00'] as $h) {
      $s = $horario==$h ? "selected" : "";
      echo "<option value='$h' $s>$h</option>";
    } ?>
  </select>
  <button type="submit">Atualizar</button>
</form>

<h2>Assento</h2>

<form method="POST" action="pedido.php">
  <input type="hidden" name="filme" value="<?= htmlspecialchars($filme) ?>">
  <input type="hidden" name="data" value="<?= $data ?>">
  <input type="hidden" name="horario" value="<?= $horario ?>">
  
  Nome: <input name="nome_cliente" required><br>
  
  Assento:
  <select name="assento">
    <?php foreach(['A1','A2','A3','A4','A5','A6','A7','A8','A9','A10'] as $a) {
      $d = in_array($a, $ocupados) ? "disabled" : "";
      $txt = $d ? "$a (Indisponível)" : $a;
      echo "<option value='$a' $d>$txt</option>";
    } ?>
  </select><br><br>

  <button type="submit">Fazer Pedido</button>
  <a href="?filme=<?= $filme ?>&data=<?= $data ?>&horario=<?= $horario ?>&mostrar=1">
    <button type="button">Ver Pedidos</button>
  </a>
</form>

<?php if (isset($_GET['mostrar']) && $_GET['mostrar'] == 1): ?>
  <h2>Pedidos Realizados</h2>
  <table border="1" cellpadding="5">
    <tr>
      <th>Cliente</th>
      <th>Filme</th>
      <th>Data</th>
      <th>Horário</th>
      <th>Assentos</th>
    </tr>
    <?php
      $sql = "SELECT 
                nome_cliente,
                f.nome AS filme,
                data,
                horario,
                GROUP_CONCAT(assento ORDER BY assento SEPARATOR ', ') AS assentos
              FROM pedidos p
              JOIN filmes f ON f.id = p.filme_id
              GROUP BY nome_cliente, filme_id, data, horario
              ORDER BY data DESC, horario";
      $res = $conn->query($sql);
      while ($r = $res->fetch_assoc()):
    ?>
      <tr>
        <td><?= $r['nome_cliente'] ?></td>
        <td><?= $r['filme'] ?></td>
        <td><?= $r['data'] ?></td>
        <td><?= $r['horario'] ?></td>
        <td><?= $r['assentos'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php endif; ?>
</body>
</html>
