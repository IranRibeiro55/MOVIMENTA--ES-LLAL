<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$erro = '';
$sucesso = false;

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaNova = $_POST['nova_senha'] ?? '';
    $senhaConfirmar = $_POST['confirmar_senha'] ?? '';

    if (empty($senhaNova) || empty($senhaConfirmar)) {
        $erro = "Preencha todos os campos.";
    } elseif ($senhaNova !== $senhaConfirmar) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senhaNova) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $senhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 0 WHERE id = ?");
        $stmt->bind_param("si", $senhaHash, $usuarioId);
        if ($stmt->execute()) {
            $sucesso = true;
            header("Location: ?page=home");
            exit;
        } else {
            $erro = "Erro ao atualizar a senha.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Trocar Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md space-y-4">
        <h2 class="text-xl font-bold text-center text-red-600">🔐 Primeiro Acesso</h2>
        <p class="text-gray-700 text-center">Você precisa cadastrar uma nova senha para continuar.</p>

        <?php if ($erro): ?>
            <p class="text-red-600 text-sm"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label>Nova Senha:</label>
                <input type="password" name="nova_senha" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label>Confirmar Senha:</label>
                <input type="password" name="confirmar_senha" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Salvar</button>
        </form>
    </div>
</body>
</html>
