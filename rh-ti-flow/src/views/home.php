<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$nome = $_SESSION['nome'] ?? 'Desconhecido';
$perfil = $_SESSION['perfil'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>RH Movimentações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeSlide 1s ease-out forwards;
        }

        .btn-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-animate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-disabled {
            background-color: #d1d5db;
            color: #6b7280;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-16"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>

    <div class="relative z-10 w-full max-w-xl mx-auto bg-white bg-opacity-90 backdrop-blur-md p-10 rounded-2xl shadow-2xl fade-in space-y-6">

        <h1 class="text-3xl font-bold text-center text-gray-900 drop-shadow-sm">
            🎉 Bem-vindo, <span class="text-blue-600"><?= htmlspecialchars($nome) ?></span>!
        </h1>

        <div class="grid gap-4">
            <?php if ($perfil === 'analista_rh'): ?>
                <a href="?page=nova_solicitacao" class="btn-animate flex items-center gap-3 bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition">
                    ➕ <span>Nova Solicitação</span>
                </a>
                <a href="?page=pendencias&setor=rh" class="btn-animate flex items-center gap-3 bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition">
                    📌 <span>Pendências RH</span>
                </a>
                <a href="?page=historico" class="btn-animate flex items-center gap-3 bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    📂 <span>Histórico</span>
                </a>
                <a href="?page=listar_vagas" class="btn-animate flex items-center gap-3 btn-disabled px-6 py-3 rounded-lg">
                    📋 <span>Vagas Previstas (em construção)</span>
                </a>

            <?php elseif ($perfil === 'gestor_rh'): ?>
                <a href="?page=painel_gestor" class="btn-animate flex items-center gap-3 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    ✅ <span>Aprovar Solicitações</span>
                </a>
                <div class="btn-animate flex items-center gap-3 btn-disabled px-6 py-3 rounded-lg">
                    🛠️ <span>Editar Solicitação (em construção)</span>
                </div>
                <a href="?page=dashboard" class="btn-animate flex items-center gap-3 bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 transition">
                    📊 <span>Dashboard</span>
                </a>
                <a href="?page=historico" class="btn-animate flex items-center gap-3 bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    📂 <span>Histórico</span>
                </a>
                <a href="?page=listar_vagas" class="btn-animate flex items-center gap-3 btn-disabled px-6 py-3 rounded-lg">
                    📋 <span>Vagas Previstas (em construção)</span>
                </a>

            <?php elseif ($perfil === 'analista_ti'): ?>
                <a href="?page=painel_ti" class="btn-animate flex items-center gap-3 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    💻 <span>Painel TI</span>
                </a>
                <a href="?page=pendencias&setor=ti" class="btn-animate flex items-center gap-3 bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition">
                    📌 <span>Pendências TI</span>
                </a>
                <a href="?page=dashboard" class="btn-animate flex items-center gap-3 bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 transition">
                    📊 <span>Dashboard</span>
                </a>
                <a href="?page=cadastrar_usuario" class="btn-animate flex items-center gap-3 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    👤 <span>Cadastrar Usuário</span>
                </a>
                <a href="?page=redefinir_senha" class="btn-animate flex items-center gap-3 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">
                    🔑 <span>Redefinir Senha</span>
                </a>
                <a href="?page=historico" class="btn-animate flex items-center gap-3 bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    📂 <span>Histórico</span>
                </a>
                <a href="?page=listar_vagas" class="btn-animate flex items-center gap-3 btn-disabled px-6 py-3 rounded-lg">
                    📋 <span>Vagas Previstas (em construção)</span>
                </a>
            <?php endif; ?>

            <!-- Botão de sair -->
            <a href="?page=logout" class="btn-animate flex items-center gap-3 bg-red-700 text-white px-6 py-3 rounded-lg hover:bg-red-800 transition mt-2">
                🧾 <span>Sair</span>
            </a>
        </div>
    </div>
</body>
</html>
