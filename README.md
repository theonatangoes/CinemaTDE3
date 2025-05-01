# CinemaTDE3

Sistema simples de controle de cinema desenvolvido em PHP com MySQL, como atividade prática da faculdade para treinar conceitos de backend.

## Sobre o projeto

Este sistema permite:
- Cadastrar pedidos de ingresso para filmes disponíveis
- Visualizar relatórios de pedidos
- Editar ou excluir pedidos

## Tecnologias utilizadas

- PHP
- MySQL
- XAMPP (Apache + MySQL)
- HTML/CSS básico

## Estrutura de Arquivos

- conexao.php – Conexão com o banco de dados
- index.php – Página principal com formulário de pedido
- pedido.php – Processamento do pedido e inserção no banco
- editar.php – Editar pedido
- excluir.php – Excluir pedido
- relatorio.php – Exibir todos os pedidos

## Como rodar o projeto no XAMPP

### 1. Clone o repositório
bash
git clone https://github.com/theonatangoes/CinemaTDE3


### 2. Mova para a pasta do XAMPP
Copie a pasta clonada (CinemaTDE3) para dentro da pasta htdocs do XAMPP.

Exemplo:

C:\xampp\htdocs\CinemaTDE3


### 3. Crie o banco de dados
1. Acesse o *phpMyAdmin* (http://localhost/phpmyadmin)
2. Clique em *SQL* e cole o script abaixo para criar o banco e as tabelas:

sql
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

INSERT INTO filmes (nome) VALUES 
('Minicraft'), 
('Jumanji'), 
('The Chosen'), 
('Não Entre'), 
('Pecadores');


### 4. Configure o XAMPP
- Inicie o *Apache* e o *MySQL* no painel de controle do XAMPP

### 5. Acesse o projeto
No navegador, abra:

http://localhost/CinemaTDE3/index.php


## Requisitos para funcionamento

- XAMPP instalado (https://www.apachefriends.org/)
- Navegador atualizado
- PHP 7.4 ou superior

## Equipe

Feito por [@theonatangoes](https://github.com/theonatangoes) – Projeto acadêmico da disciplina de Backend.