CREATE DATABASE IF NOT EXISTS cinema;
USE cinema;

CREATE TABLE IF NOT EXISTS filmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(100) NOT NULL,
    cpf CHAR(11) NOT NULL,
    filme_id INT NOT NULL,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    assento VARCHAR(10) NOT NULL,
    FOREIGN KEY (filme_id) REFERENCES filmes(id)
);

INSERT INTO filmes (titulo) VALUES 
('Minicraft'), 
('The Chosen'), 
('Jumanji');
