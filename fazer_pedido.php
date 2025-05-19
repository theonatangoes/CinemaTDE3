<?php
require_once 'classes/Database.php';
require_once 'classes/Pedido.php';
require_once 'classes/Filme.php';

$db = new Database();
$conn = $db->conectar();
$pedido = new Pedido($conn);
$filmeObj = new Filme($conn);
$filmes = $filmeObj->listar();

// Get the current date and time
$hoje = date('Y-m-d');
$currentTime = date('H:i');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $forma_pagamento = $_POST['forma_pagamento'];

    // Validação de CPF: apenas números e 11 dígitos
    if (!preg_match('/^\d{11}$/', $cpf)) {
        die("Erro: CPF inválido. Digite exatamente 11 números.");
    }

    $filme_id = $_POST['filme_id'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $assentos = $_POST['assentos'] ?? [];

    // Verificação de pelo menos um assento
    if (empty($assentos)) {
        die("Erro: Selecione pelo menos um assento.");
    }

    // Verificação de data no passado
    if (strtotime($data) < strtotime($hoje)) {
        die('Erro: Data inválida.');
    }

    if ($pedido->fazer($nome, $cpf, $filme_id, $data, $horario, $assentos, $forma_pagamento)) {
        echo "Pedido realizado com sucesso!";
    } else {
        echo "Erro ao realizar pedido (limite de cadeiras pode ter sido excedido).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FAP Flix</title>
    <style>
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 110px;
            width: auto;
        }
        h1 {
            margin: 0;
            font-size: 2em;
        }
    </style>
<script>
        // Função para verificar os horários disponíveis com base na data selecionada
        function checkAvailableTimes() {
            const dateInput = document.getElementById('data');
            const timeSelect = document.getElementById('horario');
            const selectedDate = dateInput.value;
            const today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
            
            // Resetar todas as opções de horário
            for (let i = 0; i < timeSelect.options.length; i++) {
                timeSelect.options[i].disabled = false;
            }
            
            // Se for o dia de hoje, desabilitar horários que já passaram
            if (selectedDate === today) {
                const now = new Date();
                const currentHour = now.getHours();
                const currentMinute = now.getMinutes();
                
                for (let i = 0; i < timeSelect.options.length; i++) {
                    const timeOption = timeSelect.options[i].value;
                    const [hour, minute] = timeOption.split(':').map(Number);
                    
                    if (hour < currentHour || (hour === currentHour && minute <= currentMinute)) {
                        timeSelect.options[i].disabled = true;
                    }
                }
                
                // Se o horário atualmente selecionado estiver desabilitado, selecione o próximo disponível
                if (timeSelect.selectedIndex >= 0 && timeSelect.options[timeSelect.selectedIndex].disabled) {
                    for (let i = 0; i < timeSelect.options.length; i++) {
                        if (!timeSelect.options[i].disabled) {
                            timeSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }
        }
        
        // Executar quando o documento estiver carregado
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('data');
            
            // Aplicar restrição de data mínima
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
            
            // Verificar horários disponíveis na carga da página
            checkAvailableTimes();
            
            // Adicionar listener para mudança de data
            dateInput.addEventListener('change', checkAvailableTimes);
        });
    </script>
</head>
<body>

<div class="header">
    <img src="FapFlix.png" alt="Logo FAP Flix">
</div>
<h3>Fazer Pedido</h3>

<form method="post">
    Nome: <input type="text" name="nome" required><br>
    CPF: <input type="text" name="cpf" maxlength="11" required><br>
    Filme:
    <select name="filme_id" required>
        <?php foreach ($filmes as $filme): ?>
            <option value="<?= $filme['id'] ?>"><?= $filme['titulo'] ?></option>
        <?php endforeach; ?>
    </select><br>
    Data: <input type="date" name="data" id="data" min="<?= $hoje ?>" required><br>
    Horário:
    <select name="horario" id="horario" required>
        <option value="14:00">14:00</option>
        <option value="18:00">18:00</option>
        <option value="20:00">20:00</option>
    </select><br>
    Assentos:<br>
    <?php foreach (range(1, 10) as $i): ?>
        <label><input type="checkbox" name="assentos[]" value="A<?= $i ?>">A<?= $i ?></label>
    <?php endforeach; ?>
    <br><br>
    Forma de Pagamento:
    <select name="forma_pagamento" required>
        <option value="PIX">PIX</option>
        <option value="Cartão de Crédito">Cartão de Crédito</option>
        <option value="Cartão de Débito">Cartão de Débito</option>
        <option value="Dinheiro">Dinheiro</option>
    </select><br><br>
    <button type="submit">Confirmar Pedido</button>
</form>

<br>
<a href="ver_pedido.php"><button>Ver Pedidos</button></a><br>
<a href="gerenciar_filmes.php"><button>Gerenciar Filmes</button></a>
</body>
</html>
