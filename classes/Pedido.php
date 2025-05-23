<?php
class Pedido {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fazer($nome, $cpf, $filme_id, $data, $horario, $assentos, $forma_pagamento = null) {
        // Verifica se a quantidade de assentos não ultrapassa o limite de 4
        if (count($assentos) > 4) return false;

        // Verifica se o CPF já tem 4 ou mais reservas para essa sessão
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM pedidos WHERE cpf = :cpf AND filme_id = :filme_id AND data = :data AND horario = :horario");
        $stmt->execute([
            ':cpf' => $cpf,
            ':filme_id' => $filme_id,
            ':data' => $data,
            ':horario' => $horario
        ]);
        $qtd_existente = $stmt->fetchColumn();
        if ($qtd_existente + count($assentos) > 4) return false;

        // Verifica se os assentos já estão ocupados na mesma sessão
        $ocupados = $this->cadeirasOcupadas($filme_id, $data, $horario);
        foreach ($assentos as $assento) {
            if (in_array($assento, $ocupados)) {
                return false; // Assento já ocupado
            }
        }

        // Insere os pedidos com o nome, CPF, filme, data, horário, assento e forma de pagamento
        foreach ($assentos as $assento) {
            $stmt = $this->conn->prepare("INSERT INTO pedidos (nome_cliente, cpf, filme_id, data, horario, assento, forma_pagamento) VALUES (:nome, :cpf, :filme_id, :data, :horario, :assento, :forma_pagamento)");
            $stmt->execute([
                ':nome' => $nome,
                ':cpf' => $cpf,
                ':filme_id' => $filme_id,
                ':data' => $data,
                ':horario' => $horario,
                ':assento' => $assento,
                ':forma_pagamento' => $forma_pagamento
            ]);
        }

        return true;
    }

    // Lista todos os pedidos feitos
    public function listarTodos() {
        $stmt = $this->conn->query("SELECT pedidos.*, filmes.titulo FROM pedidos JOIN filmes ON pedidos.filme_id = filmes.id ORDER BY data DESC, horario DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista pedidos de um CPF específico
    public function listarPorCPF($cpf) {
        $stmt = $this->conn->prepare("SELECT pedidos.*, filmes.titulo FROM pedidos JOIN filmes ON pedidos.filme_id = filmes.id WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $cpf]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Remove um pedido pelo ID
    public function removerPorId($id) {
        $stmt = $this->conn->prepare("DELETE FROM pedidos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Retorna todos os assentos ocupados para uma sessão
    public function cadeirasOcupadas($filme_id, $data, $horario) {
        $stmt = $this->conn->prepare("SELECT assento FROM pedidos WHERE filme_id = :filme_id AND data = :data AND horario = :horario");
        $stmt->execute([
            ':filme_id' => $filme_id,
            ':data' => $data,
            ':horario' => $horario
        ]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function editarPorId($id, $nome, $cpf, $forma_pagamento) {
    $stmt = $this->conn->prepare("UPDATE pedidos SET nome_cliente = :nome, cpf = :cpf, forma_pagamento = :forma_pagamento WHERE id = :id");
    return $stmt->execute([
        ':nome' => $nome,
        ':cpf' => $cpf,
        ':forma_pagamento' => $forma_pagamento,
        ':id' => $id
    ]);
}

}
?>
