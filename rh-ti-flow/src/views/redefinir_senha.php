<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['perfil'] !== 'analista_ti') {
    echo "<p class='text-red-600 p-4'>⛔ Acesso negado. Somente analista de TI pode redefinir senhas.</p>";
    exit;
}

$msg = '';
$msgClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if (empty($usuario) || empty($novaSenha) || $novaSenha !== $confirmarSenha) {
        $msg = "❌ Verifique os campos preenchidos. As senhas devem coincidir.";
        $msgClass = "text-red-700 bg-red-100 border border-red-300 p-4 rounded shadow";
    } else {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 1 WHERE usuario = ?");
        $stmt->bind_param("ss", $hash, $usuario);

        if ($stmt->execute()) {
            $msg = "✅ Senha redefinida com sucesso!";
            $msgClass = "text-green-700 bg-green-100 border border-green-300 p-4 rounded shadow";
        } else {
            $msg = "❌ Erro ao redefinir a senha.";
            $msgClass = "text-red-700 bg-red-100 border border-red-300 p-4 rounded shadow";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Redefinir Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.7s ease forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-10"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>

    <div class="relative z-10 max-w-xl w-full mx-auto bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl fade-in space-y-6">

        <!-- Cabeçalho ajustado -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="?page=home"
               class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-2.5 rounded-md shadow hover:bg-blue-700 transition text-sm md:text-base font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>

            <h1 class="text-xl md:text-2xl font-semibold text-gray-800 text-center flex-1 text-balance">
                🔑 Redefinir Senha de Usuário
            </h1>

            <!-- Espaço fantasma pra centralizar título -->
            <div class="w-[96px] hidden md:block"></div>
        </div>

        <!-- Mensagem -->
        <?php if ($msg): ?>
            <div class="<?= $msgClass ?> text-center text-sm animate-pulse">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário -->
        <form method="POST" class="space-y-6">
            <div>
                <label for="usuario" class="block font-semibold text-gray-700 mb-1">Usuário</label>
                <select id="usuario" name="usuario" required
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Selecione --</option>
                    <?php
                    $res = $conn->query("SELECT usuario, nome FROM usuarios ORDER BY nome");
                    while ($u = $res->fetch_assoc()) {
                        echo "<option value='".htmlspecialchars($u['usuario'])."'>".htmlspecialchars($u['nome'])." (".htmlspecialchars($u['usuario']).")</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="nova_senha" class="block font-semibold text-gray-700 mb-1">Nova Senha</label>
                <input id="nova_senha" type="password" name="nova_senha" required
                       class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="confirmar_senha" class="block font-semibold text-gray-700 mb-1">Confirmar Senha</label>
                <input id="confirmar_senha" type="password" name="confirmar_senha" required
                       class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Botão de enviar -->
            <div class="flex justify-center pt-2">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition font-semibold flex items-center gap-2">
                    🔁 <span>Redefinir Senha</span>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
