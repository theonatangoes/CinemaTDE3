<?php
require_once 'classes/Database.php';
require_once 'classes/Filme.php';

$db = new Database();
$conn = $db->conectar();
$filme = new Filme($conn);
$filmes = $filme->listar();
$hoje = date('Y-m-d');
$horarios = ['14:00', '18:00', '20:00'];

// Get the current time
$currentTime = date('H:i');
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

<h2>Fazer Pedido</h2>
<form action="fazer_pedido.php" method="post">
    <label>Nome:</label><input type="text" name="nome" required><br>
    <p></p>
    <label>CPF:</label><input type="text" name="cpf" maxlength="11" required><br>
    <p></p>
    <label>Filme:</label>
    <select name="filme_id" required>
        <?php foreach ($filmes as $f): ?>
            <option value="<?= $f['id'] ?>"><?= $f['titulo'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <p></p>
    <label>Data:</label><input type="date" name="data" id="data" min="<?= $hoje ?>" required><br>
    <p></p>
    <label>Horário:</label>
    <select name="horario" id="horario" required>
        <?php foreach ($horarios as $h): ?>
            <option value="<?= $h ?>"><?= $h ?></option>
        <?php endforeach; ?>
    </select><br>
    <p></p>
    <label>Assentos (máx 4):</label><br>
    <?php for ($i = 1; $i <= 10; $i++): ?>
        <input type="checkbox" name="assentos[]" value="A<?= $i ?>">A<?= $i ?>
    <?php endfor; ?><br>
    <p></p>
    <label>Forma de Pagamento:</label>
    <select name="forma_pagamento" required>
        <option value="PIX">PIX</option>
        <option value="Cartão de Crédito">Cartão de Crédito</option>
        <option value="Cartão de Débito">Cartão de Débito</option>
        <option value="Dinheiro">Dinheiro</option>
    </select><br>
    <p></p>
    <button type="submit">Confirmar Pedido</button>
</form>

<br><a href="ver_pedido.php"><button>Ver Pedidos</button></a>
<br><a href="gerenciar_filmes.php"><button>Gerenciar Filmes</button></a>

</body>
</html>
