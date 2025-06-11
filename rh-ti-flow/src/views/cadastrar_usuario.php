<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$perfil = $_SESSION['perfil'] ?? '';

if ($perfil !== 'analista_ti') {
    echo "<p style='color:red; padding:20px;'>⛔ Acesso negado. Apenas Analista de TI pode cadastrar usuários.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
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

    <div class="relative z-10 max-w-xl mx-auto w-full bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl fade-in space-y-6">

        <!-- Cabeçalho -->
        <div class="relative flex items-center justify-center mb-4">
            <div class="absolute left-0">
                <a href="?page=home"
                   class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-2.5 rounded-md shadow hover:bg-blue-700 transition text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Voltar
                </a>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 text-center">
                👥 Cadastrar Novo Usuário
            </h1>
        </div>

        <!-- Formulário -->
        <form action="?page=salvar_usuario" method="POST" class="space-y-5 text-base">

            <div>
                <label for="nome" class="block font-medium mb-1">Nome completo:</label>
                <input id="nome" type="text" name="nome" required
                       class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="usuario" class="block font-medium mb-1">Usuário (login):</label>
                <input id="usuario" type="text" name="usuario" required
                       class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="senha" class="block font-medium mb-1">Senha:</label>
                <input id="senha" type="password" name="senha" required
                       class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="perfil" class="block font-medium mb-1">Perfil:</label>
                <select id="perfil" name="perfil" required
                        class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Selecione --</option>
                    <option value="analista_rh">Analista RH</option>
                    <option value="analista_ti">Analista TI</option>
                    <option value="gestor_rh">Gestor RH</option>
                    <option value="contratacao">Contratação</option>
                </select>
            </div>

            <div>
                <label class="inline-flex items-center text-base">
                    <input type="checkbox" name="ativo" value="1" checked class="mr-3 w-5 h-5 text-blue-600 rounded focus:ring-blue-500" />
                    Usuário Ativo
                </label>
            </div>

            <!-- Botão de salvar centralizado -->
            <div class="flex justify-center pt-4">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition font-semibold flex items-center gap-2">
                    💾 <span>Salvar</span>
                </button>
            </div>

        </form>
    </div>
</body>
</html>
