<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?openLogin');
    exit();
}

$user = $_SESSION['usuario'];
$cargo = $user['cargo'];
$userId = $user['id'];

// Verifica se é professor e se recebeu o ID via POST
if ($cargo !== 'Professor' || !isset($_POST['id'])) {
    header('Location: painelProfessor.php');
    exit();
}

$atividadeId = intval($_POST['id']);

// Garante que o professor só pode excluir suas próprias atividades
$stmt = $conn->prepare("DELETE FROM atividades WHERE id = ? AND professor_id = ?");
$stmt->execute([$atividadeId, $userId]);

echo "<script>alert('Atividade excluída com sucesso!'); window.location.href='painelProfessor.php';</script>";
exit();
?>
