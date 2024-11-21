<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "root"; // substitua com seu nome de usuário
$password = ""; // substitua com sua senha
$dbname = "FashionSketch"; // nome do banco de dados

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha

// Inserir dados no banco de dados
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Cadastro realizado com sucesso!"));
} else {
    if ($conn->errno == 1062) { // Código de erro para violação de chave única
        echo json_encode(array("success" => false, "message" => "Este e-mail já está registrado."));
    } else {
        echo json_encode(array("success" => false, "message" => "Erro ao cadastrar: " . $conn->error));
    }
}

$conn->close();
?>