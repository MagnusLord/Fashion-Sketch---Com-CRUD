<?php
// telainicial.php

// Configurações do banco de dados
$servername = "localhost";
$username = "root"; // substitua com seu nome de usuário
$password = ""; // substitua com sua senha
$dbname = "FashionSketch"; // nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Lidar com o salvamento de itens da galeria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $nome = $_POST['nome'];
    $imagemData = $_POST['imagemData'];
    // Remover o prefixo 'data:image/png;base64,'
    $imagemData = str_replace('data:image/png;base64,', '', $imagemData);
    // Decodificar os dados base64
    $imagemData = base64_decode($imagemData);

    $sql = "INSERT INTO gallery_items (nome, imagem) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sb", $nome, $imagemData);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => $stmt->error]);
    }

    $stmt->close();
}

// Lidar com a recuperação de itens da galeria
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'buscar') {
    $sql = "SELECT * FROM gallery_items";
    $result = $conn->query($sql);

    $itens = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $itens[] = array(
                'id' => $row['id'],
                'nome' => $row['nome'],
                'imagem' => 'data:image/png;base64,' . base64_encode($row['imagem']),
                'criadoEm' => $row['criadoEm']
            );
        }
    }

    echo json_encode($itens);
}

$conn->close();
?>