<?php
require 'conexao.php';
session_start();

// Verifica se é suporte
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['cargo'] !== 'Suporte') {
    header('Location: login.php');
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $cargo = $_POST['cargo'];

    if ($nome && $email && $senha && $cargo) {
        try {
            // Criptografa a senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Inserção no banco
            $sql = "INSERT INTO usuarios (nome, email, senha, cargo, criado_em)
                    VALUES (:nome, :email, :senha, :cargo, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_hash,
                ':cargo' => $cargo
            ]);

            $mensagem = "Funcionário cadastrado com sucesso!";
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f9fafb;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        header {
            background-color: #260ab2;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        form {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #260ab2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #1e0890;
        }
        .mensagem {
            text-align: center;
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }
        .voltar {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #260ab2;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <h2>Cadastrar Novo Funcionário</h2>
        <a href="painel_suporte.php" class="logout">Voltar</a>
    </header>

    <form method="POST">
        <label>Nome Completo:</label>
        <input type="text" name="nome" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <label>Cargo:</label>
        <select name="cargo" required>
            <option value="">Selecione...</option>
            <option value="Suporte">Suporte</option>
            <option value="Professor">Professor</option>
            <option value="Aluno">Aluno</option>
        </select>

        <button type="submit">Cadastrar</button>

        <?php if ($mensagem): ?>
            <p class="mensagem"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
    </form>
</body>
</html>