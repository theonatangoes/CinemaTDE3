<?php
class Filme {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM filmes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionar($titulo) {
        $stmt = $this->conn->prepare("INSERT INTO filmes (titulo) VALUES (:titulo)");
        return $stmt->execute([':titulo' => $titulo]);
    }

    public function remover($id) {
        $stmt = $this->conn->prepare("DELETE FROM filmes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
