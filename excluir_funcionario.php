<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['cargo'] !== 'Suporte') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header('Location: painel_suporte.php');
exit;
?>