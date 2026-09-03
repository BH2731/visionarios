<?php
session_start();
require 'conexao.php';

// Verifica login
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?openLogin');
    exit();
}

$user = $_SESSION['usuario'];
$cargo = $user['cargo'];
$userId = $user['id'];

// Verifica se é professor
if ($cargo !== 'Professor') {
    header('Location: painelProfessor.php');
    exit();
}

// Recebe o ID da atividade
if (!isset($_GET['id'])) {
    header('Location: painelProfessor.php');
    exit();
}

$atividadeId = intval($_GET['id']);

// Busca a atividade
$stmt = $conn->prepare("SELECT * FROM atividades WHERE id = ? AND professor_id = ?");
$stmt->execute([$atividadeId, $userId]);
$atividade = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$atividade) {
    echo "Atividade não encontrada ou você não tem permissão para editar.";
    exit();
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];

    $stmt = $conn->prepare("UPDATE atividades SET titulo = ?, descricao = ?, data = ? WHERE id = ? AND professor_id = ?");
    $stmt->execute([$titulo, $descricao, $data, $atividadeId, $userId]);

    echo "<script>alert('Atividade editada com sucesso!'); window.location.href='painelProfessor.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Atividade</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Editar Atividade</h2>
    <form method="POST" class="space-y-3">
        <label class="block">Data:
            <input type="date" name="data" value="<?php echo htmlspecialchars($atividade['data']); ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="block">Título:
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($atividade['titulo']); ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="block">Descrição:
            <textarea name="descricao" class="w-full border rounded p-2" required><?php echo htmlspecialchars($atividade['descricao']); ?></textarea>
        </label>
        <div class="flex justify-between">
            <a href="painelProfessor.php" class="bg-gray-400 px-4 py-2 rounded hover:bg-gray-500 text-white">Cancelar</a>
            <button type="submit" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 text-white">Salvar</button>
        </div>
    </form>
</div>
</body>
</html>