<?php
class Pedido {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fazer($nome, $cpf, $filme_id, $data, $horario, $assentos) {
        if (count($assentos) > 4) return false;

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM pedidos WHERE cpf = :cpf AND filme_id = :filme_id AND data = :data AND horario = :horario");
        $stmt->execute([':cpf' => $cpf, ':filme_id' => $filme_id, ':data' => $data, ':horario' => $horario]);
        if ($stmt->fetchColumn() + count($assentos) > 4) return false;

        foreach ($assentos as $assento) {
            $stmt = $this->conn->prepare("INSERT INTO pedidos (nome_cliente, cpf, filme_id, data, horario, assento) VALUES (:nome, :cpf, :filme_id, :data, :horario, :assento)");
            $stmt->execute([
                ':nome' => $nome,
                ':cpf' => $cpf,
                ':filme_id' => $filme_id,
                ':data' => $data,
                ':horario' => $horario,
                ':assento' => $assento
            ]);
        }
        return true;
    }

    public function listarPorCPF($cpf) {
        $stmt = $this->conn->prepare("SELECT pedidos.*, filmes.titulo FROM pedidos JOIN filmes ON pedidos.filme_id = filmes.id WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $cpf]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removerPorId($id) {
        $stmt = $this->conn->prepare("DELETE FROM pedidos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function cadeirasOcupadas($filme_id, $data, $horario) {
        $stmt = $this->conn->prepare("SELECT assento FROM pedidos WHERE filme_id = :filme_id AND data = :data AND horario = :horario");
        $stmt->execute([':filme_id' => $filme_id, ':data' => $data, ':horario' => $horario]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
