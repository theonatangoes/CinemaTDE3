CREATE DATABASE cinema;
USE cinema;

CREATE TABLE filmes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100)
);

CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_cliente VARCHAR(100),
  filme_id INT,
  data DATE,
  horario TIME,
  assento VARCHAR(10),
  FOREIGN KEY (filme_id) REFERENCES filmes(id)
);

INSERT INTO filmes (nome) VALUES ('Minicraft'), ('Jumanji'), ('The Chosen'), ('Não Entre'), ('Pecadores');
