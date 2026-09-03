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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visionários - Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="./img/olho logo.png"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <header class="bg-[#260ab2] text-white py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="imgs/logo.png" alt="Logo" class="w-10 h-10 rounded-full">
                <h1 class="text-xl font-semibold">Painel Administrativo</h1>
            </div>

            <nav class="flex items-center gap-8">
                <a href="cadastro_funcionarios.php" class="hover:text-gray-200 transition">
                    <i class="fa-solid fa-user-plus mr-1"></i>Cadastro de Funcionários
                </a>
                <a href="log_funcionarios.php" class="hover:text-gray-200 transition">
                    <i class="fa-solid fa-list mr-1"></i>Registro de Funcionários
                </a>
                <a href="logout.php" class="bg-red-600 px-3 py-2 rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair
                </a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="bg-white p-6 rounded-2xl shadow hover-glow transition">
            <h2 class="text-2xl font-semibold mb-4 text-[#260ab2]">
                Cadastro de Funcionários
            </h2>
            
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
        </div>
    </main>
</body>
</html>