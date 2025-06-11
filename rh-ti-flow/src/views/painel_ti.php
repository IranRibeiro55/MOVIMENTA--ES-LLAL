<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'analista_ti') {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$stmt = $conn->prepare("
    SELECT id, tipo, usuario, criado_em 
    FROM solicitacoes 
    WHERE status_ti = 'pendente' 
    AND retornar_para IS NULL
    ORDER BY criado_em DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        .btn-link {
            @apply text-blue-600 hover:text-blue-800 font-semibold transition duration-200 ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-20"
      style="background-image: url('assets/img/Wallpapers.png');">

    <!-- Fundo escuro -->
    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <!-- Painel principal -->
    <div class="relative z-10 max-w-5xl mx-auto bg-white bg-opacity-90 backdrop-blur-md p-8 md:p-10 rounded-2xl shadow-2xl fade-in space-y-6">

        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-6">
            <a href="?page=home"
               class="inline-flex items-center gap-2 text-white bg-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar para Home
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                🛠️ Aprovações do Analista de TI
            </h1>
        </div>

        <!-- Lista ou mensagem -->
        <?php if ($result->num_rows === 0): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow text-center">
                ✅ Nenhuma solicitação nova para aprovação.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm mt-4 rounded overflow-hidden shadow">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Criado por</th>
                            <th class="px-4 py-2 text-left">Data</th>
                            <th class="px-4 py-2 text-left">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-b hover:bg-gray-100 transition">
                                <td class="px-4 py-2"><?= $row['id'] ?></td>
                                <td class="px-4 py-2"><?= ucfirst($row['tipo']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['usuario'] ?? '---') ?></td>
                                <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                                <td class="px-4 py-2">
                                    <a href="?page=acao_ti&id=<?= $row['id'] ?>" class="btn-link flex items-center gap-1">
                                        🔍 <span>Ver</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
