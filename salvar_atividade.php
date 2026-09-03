<?php
session_start();
include 'conexao.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?openLogin');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados enviados pelo formulário
    $data = $_POST['data'] ?? null;
    $titulo = $_POST['titulo'] ?? null;
    $descricao = $_POST['descricao'] ?? null;
    $professor_id = $_POST['professor_id'] ?? null;

    if ($data && $titulo && $descricao && $professor_id) {
        try {
            $stmt = $pdo->prepare("INSERT INTO atividades (data, titulo, descricao, professor_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data, $titulo, $descricao, $professor_id]);

            // Redireciona para o calendário
            header('Location: painelProfessor.php?sucesso=1');
            exit();
        } catch (PDOException $e) {
            die("Erro ao salvar atividade: " . $e->getMessage());
        }
    } else {
        die("Preencha todos os campos do formulário!");
    }
} else {
    header('Location: painelProfessor.php');
    exit();
}