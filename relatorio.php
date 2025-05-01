<?php
include "conexao.php";

if (isset($_GET['resetar']) && $_GET['resetar'] === '1') {
    $conn->query("DELETE FROM pedidos");
    echo "<p style='color: red; font-weight: bold;'>✅ Todos os assentos foram liberados!</p>";
}

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

$result = $conn->query($sql);

echo "<h1>Relatório de Pedidos</h1>";
echo "<p><a href='relatorio.php?resetar=1' onclick='return confirm(\"Tem certeza que deseja liberar todos os assentos?\")'>
🧹 Resetar todos os assentos</a></p>";

echo "<table border='1' cellpadding='5'>
<tr>
  <th>Cliente</th>
  <th>Filme</th>
  <th>Data</th>
  <th>Horário</th>
  <th>Assentos</th>
</tr>";

while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['nome_cliente']}</td>
    <td>{$row['filme']}</td>
    <td>{$row['data']}</td>
    <td>{$row['horario']}</td>
    <td>{$row['assentos']}</td>
  </tr>";
}
echo "</table>";
?>
