<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ⛔ Debug temporário (remova depois de testar)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificação do perfil na sessão
$usuarioLogado = $_SESSION['usuario'] ?? '';
$perfil = $_SESSION['perfil'] ?? '';

// 🔍 Debug da sessão (remover em produção)
echo "<pre style='background:#f8f8f8;border:1px solid #ccc;padding:10px;margin-bottom:10px'>";
print_r($_SESSION);
echo "</pre>";

if ($perfil !== 'contratacao') {
    echo "<p class='text-red-600 p-4 font-bold'>⛔ Acesso negado. Perfil atual: <strong>" . htmlspecialchars($perfil) . "</strong></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>📋 Painel de Vagas - Contratação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">👩‍💼 Painel da Área de Contratação</h1>

        <div class="space-y-4">
            <a href="?page=nova_vaga" class="block w-full text-center bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700">
                ➕ Cadastrar Nova Vaga
            </a>
            <a href="?page=listar_vagas" class="block w-full text-center bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700">
                📄 Ver Vagas Previstas
            </a>
        </div>

        <div class="mt-6">
            <a href="?page=logout" class="text-blue-600 hover:underline">🚪 Sair</a>
        </div>
    </div>
</body>
</html>