<?php
include 'conexao.php';

// Função para gerar os assentos (como já está no seu código)
function gerarAssentos() {
    $letras = ['A'];
    $numeros = range(1, 10);
    $assentos = [];
    foreach ($letras as $l) {
        foreach ($numeros as $n) {
            $assentos[] = $l . $n;
        }
    }
    return $assentos;
}

// Recebe os dados do formulário
$nome = $_POST['nome'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$filme = $_POST['filme'] ?? '';
$data = $_POST['data'] ?? '';
$horario = $_POST['horario'] ?? '';
$assentos = $_POST['assentos'] ?? [];

$assentosDisponiveis = gerarAssentos(); // Gera todos os assentos

// Verifica se a data é válida (não é passada)
$hoje = date("Y-m-d");
if ($data < $hoje) {
    echo "Não é possível fazer reservas para sessões em datas passadas.";
    exit;
}

// Verifica os assentos já reservados para a sessão selecionada
$assentosOcupados = [];
if ($filme && $data && $horario) {
    $stmt = $conn->prepare("SELECT assento FROM pedidos WHERE filme = ? AND data = ? AND horario = ?");
    $stmt->bind_param("sss", $filme, $data, $horario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $assentosOcupados[] = $row['assento'];
    }
    // Libera o resultado da consulta
    $stmt->free_result();
    $stmt->close();
}

// Verifica a quantidade de assentos selecionados
if (count($assentos) > 4) {
    echo "Você pode escolher no máximo 4 assentos por sessão.";
    exit;
}

// Verifica se o CPF já fez reserva para o mesmo filme, data e horário
$stmt = $conn->prepare("SELECT COUNT(*) FROM pedidos WHERE cpf = ? AND filme = ? AND data = ? AND horario = ?");
$stmt->bind_param("ssss", $cpf, $filme, $data, $horario);
$stmt->execute();
$stmt->bind_result($reservasExistentes);
$stmt->fetch();
$stmt->free_result();
$stmt->close();

if ($reservasExistentes > 0) {
    echo "Você já fez uma reserva para esta sessão.";
    exit;
}

// Verifica se algum dos assentos selecionados já foi reservado
foreach ($assentos as $assento) {
    if (in_array($assento, $assentosOcupados)) {
        echo "O assento $assento já foi reservado. Por favor, escolha outro assento.";
        exit;
    }
}

// Se o número de assentos for válido e não houver problemas, insere os dados no banco
try {
    foreach ($assentos as $assento) {
        $stmt = $conn->prepare("INSERT INTO pedidos (nome, cpf, filme, data, horario, assento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $cpf, $filme, $data, $horario, $assento);
        $stmt->execute();
    }
    echo "Pedido realizado com sucesso!";
    // Redirecionamento ou renderização de página de sucesso
    // header('Location: sucesso.php');
} catch (Exception $e) {
    echo "Erro ao realizar o pedido: " . $e->getMessage();
}
?>
