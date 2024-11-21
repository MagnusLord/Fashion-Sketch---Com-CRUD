<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "root"; // substitua com seu nome de usuário
$password = ""; // substitua com sua senha
$dbname = "FashionSketch"; // nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Criar banco de dados
$sql = "CREATE DATABASE IF NOT EXISTS FashionSketch";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado com sucesso";
} else {
    echo "Erro ao criar banco de dados: " . $conn->error;
}

// Selecionar banco de dados
$conn->select_db($dbname);

// Criar tabela de usuários
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela de usuários criada com sucesso";
} else {
    echo "Erro ao criar tabela de usuários: " . $conn->error;
}

// Criar tabela de itens da galeria
$sql = "CREATE TABLE IF NOT EXISTS gallery_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    imagem LONGBLOB NOT NULL,
    criadoEm DATETIME DEFAULT CURRENT_TIMESTAMP
    ALTER TABLE gallery_items ADD COLUMN descricao TEXT;
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela de itens da galeria criada com sucesso";
} else {
    echo "Erro ao criar tabela de itens da galeria: " . $conn->error;
}

$conn->close();
?>

