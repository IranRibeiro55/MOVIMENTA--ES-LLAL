<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioInput = trim($_POST['usuario'] ?? '');
    $senhaInput = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND ativo = 1 LIMIT 1");
    $stmt->bind_param("s", $usuarioInput);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($senhaInput, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['perfil'] = $usuario['perfil'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['usuario_id'] = $usuario['id'];

        // Primeiro acesso? Vai trocar a senha
        if ((int)$usuario['primeiro_acesso'] === 1) {
            header('Location: ?page=trocar_senha');
            exit;
        }

        // Acesso normal
        header('Location: ?page=home');
        exit;
    }

    $erro = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login RH x TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .btn-animate {
            transition: all 0.3s ease;
        }

        .btn-animate:hover {
            animation: bounceIn 0.4s ease;
        }

        .toast {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="min-h-screen flex items-start justify-center bg-cover bg-center relative pt-20"
      style="background-image: url('assets/img/Wallpapers.png');">

    <!-- Overlay escurecido -->
    <div class="absolute inset-0 bg-black bg-opacity-40 z-0"></div>

    <!-- Caixa de login -->
    <div class="relative z-10 w-full max-w-md bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl fade-in">

        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">🔐 Movimentações RH</h1>

        <!-- Mensagem de erro com estilo -->
        <?php if ($erro): ?>
            <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-300 text-red-800 font-medium shadow toast flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Usuário ou senha inválidos.
            </div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Usuário:</label>
                <input type="text" name="usuario" required
                       class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Senha:</label>
                <input type="password" name="senha" required
                       class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300 shadow-md btn-animate">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>
