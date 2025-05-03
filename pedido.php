<?php
include "conexao.php";

// Check if the "Atualizar" button was clicked
if ($_POST['filme'] == '' || $_POST['data'] == '' || $_POST['horario'] == '') {
  $error = 'Por favor, atualize os dados antes de comprar o ticket.';
  // Redirect back to the index page with the error message
  header('Location: index.php?error=' . urlencode($error));
  exit;
}

$nome = mysqli_real_escape_string($conn, $_POST['nome_cliente']);
$filme = intval($_POST['filme']);
$data = $_POST['data'];
$horario = $_POST['horario'];
$assento = mysqli_real_escape_string($conn, $_POST['assento']);

$hoje = date('Y-m-d');
if ($data < $hoje) { echo "❌ Data inválida."; exit; }

$sql = "SELECT * FROM pedidos WHERE filme_id='$filme' AND data='$data' AND horario='$horario' AND assento='$assento'";
if ($conn->query($sql)->num_rows > 0) {
  echo "⚠️ Assento já reservado.";
} else {
  $conn->query("INSERT INTO pedidos (nome_cliente, filme_id, data, horario, assento)
                VALUES ('$nome', '$filme', '$data', '$horario', '$assento')");
  echo "✅ Pedido feito! <a href='index.php'>Voltar</a>";
}
?>
