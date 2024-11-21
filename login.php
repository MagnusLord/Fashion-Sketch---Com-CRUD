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
$email = $_POST['email'];
$senha = $_POST['senha'];

// Consultar banco de dados
$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Usuário encontrado
    $row = $result->fetch_assoc();
    if (password_verify($senha, $row['senha'])) {
        
        echo json_encode(array("success" => true, "message" => "Login realizado com sucesso!"));
    } else {
        
        echo json_encode(array("success" => false, "message" => "Senha incorreta."));
    }
} else {
    // Usuário não encontrado
    echo json_encode(array("success" => false, "message" => "E-mail não encontrado."));
}

$conn->close();
?>