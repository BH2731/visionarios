<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$remember = isset($_POST['remember']) && $_POST['remember'] === '1';
if ($remember) {
    session_set_cookie_params(30*24*60*60);
}
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['password'] ?? '';

if ($email === '' || $senha === '') {
    echo json_encode(['success' => false, 'message' => 'Preencha e-mail e senha.']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nome, email, senha, cargo FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($senha, $user['senha'])) {
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos.']);
    exit;
}

unset($user['senha']);
$_SESSION['usuario'] = $user;
$pagina = ($user['cargo'] === 'Suporte') ? 'painel_suporte.php' : 'index.php';


echo json_encode([
    'success' => true,
    'message' => 'Login realizado.',
    'name' => $user['nome'],
    'redirect' => $pagina
]);