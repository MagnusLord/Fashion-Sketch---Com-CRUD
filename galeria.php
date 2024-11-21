<?php

// Parâmetros de conexão com o banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "FashionSketch";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Buscar todos os itens da galeria
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
                'descricao' => $row['descricao'],
                'criadoEm' => $row['criadoEm']
            );
        }
    }
    echo json_encode($itens);
}

// Salvar um novo item da galeria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $nome = $_POST['nome'];
    $imagemData = str_replace('data:image/png;base64,', '', $_POST['imagemData']);
    $imagemData = base64_decode($imagemData);
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO gallery_items (nome, imagem, descricao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sbs", $nome, $imagemData, $descricao);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => $stmt->error]);
    }
    $stmt->close();
}

// Excluir um item da galeria pelo ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
    $id = $_POST['id'];
    $sql = "DELETE FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => $stmt->error]);
    }
    $stmt->close();
}

// Atualizar o nome e a descrição de um item da galeria pelo ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE gallery_items SET nome = ?, descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $descricao, $id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>