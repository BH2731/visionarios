<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['password'] ?? '';

if ($nome === '' || $email === '' || $senha === '') {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}

if (strlen($senha) < 6) {
    echo json_encode(['success' => false, 'message' => 'Senha deve ter ao menos 6 caracteres.']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Já existe um usuário com esse e-mail.']);
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$insert = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
try {
    $insert->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $hash
    ]);
    echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar.']);
}