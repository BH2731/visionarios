<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['cargo'] !== 'Suporte') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: painel_suporte.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];

    $sql = "UPDATE usuarios SET nome=?, email=?, cargo=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('sssi', $nome, $email, $cargo, $id);
    $stmt->execute();

    header('Location: painel_suporte.php');
    exit;
}

$resultado = $conexao->query("SELECT * FROM usuarios WHERE id = $id");
$funcionario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main style="display:flex;justify-content:center;align-items:center;min-height:100vh;background:#f9fafb;">
        <form action="" method="POST" style="background:white;padding:30px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:350px;">
            <h2 style="text-align:center;color:#260ab2;">Editar Funcionário</h2>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($funcionario['nome']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($funcionario['email']); ?>" required>
            <select name="cargo" required>
                <option value="mestre" <?php if ($funcionario['cargo']=='mestre') echo 'selected'; ?>>Mestre</option>
                <option value="admin" <?php if ($funcionario['cargo']=='admin') echo 'selected'; ?>>Admin</option>
                <option value="operacional" <?php if ($funcionario['cargo']=='operacional') echo 'selected'; ?>>Operacional</option>
                <option value="Suporte" <?php if ($funcionario['cargo']=='Suporte') echo 'selected'; ?>>Suporte</option>
            </select>
            <button type="submit" style="background:#260ab2;color:white;padding:10px;border:none;border-radius:8px;width:100%;">Salvar Alterações</button>
        </form>
    </main>
</body>
</html>
