<?php
require 'conexao.php';
session_start();

// Verifica se é suporte
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['cargo'] !== 'Suporte') {
    header('Location: login.php');
    exit;
}

// Consulta todos os usuários, exceto suporte
$sql = "SELECT * FROM usuarios WHERE cargo != 'Suporte' ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro de Funcionários</title>
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
        nav {
            background-color: #1e0890;
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 12px 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        nav a:hover {
            color: #ffda44;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #260ab2;
            color: white;
        }
        tr:hover {
            background-color: #f1f5f9;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #E53E3E;
            color: white;
        }
        .logout {
            background-color: #E53E3E;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #260ab2;
        }
    </style>
</head>
<body>
    <header>
        <h2>Painel Administrativo - Suporte</h2>
        <a href="logout.php" class="logout">Sair</a>
    </header>

    <nav>
        <a href="cadastro_funcionarios.php">Cadastro de Funcionários</a>
        <a href="log_funcionarios.php">Registro de Funcionários</a>
    </nav>

    <h2>Lista de Funcionários</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Cargo</th>
            <th>Ações</th>
        </tr>

        <?php if (count($usuarios) > 0): ?>
            <?php foreach ($usuarios as $linha): ?>
                <tr>
                    <td><?php echo $linha['id']; ?></td>
                    <td><?php echo htmlspecialchars($linha['nome']); ?></td>
                    <td><?php echo htmlspecialchars($linha['email']); ?></td>
                    <td><?php echo ucfirst($linha['cargo']); ?></td>
                    <td>
                        <a href="editar_funcionario.php?id=<?php echo $linha['id']; ?>" class="btn btn-edit">Editar</a>
                        <a href="excluir_funcionario.php?id=<?php echo $linha['id']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum funcionário encontrado.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>