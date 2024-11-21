<?php
session_start();

$dbHost = 'localhost';
$dbName = 'fashionsketch';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $bio = $_POST['bio'];
    $location = $_POST['location'];
    $website = $_POST['website'];
    $profileImage = $_POST['profileImage'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, gender = ?, bio = ?, location = ?, website = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$username, $gender, $bio, $location, $website, $profileImage, $userId]);

    echo json_encode(['success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user);
}
?>