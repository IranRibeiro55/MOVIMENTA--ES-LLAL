<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'gestor_rh') {
    header('Location: ?page=login');
    exit;
}

$stmt = $conn->prepare("SELECT id, tipo, usuario, criado_em FROM solicitacoes WHERE status_ti = 'aprovado' AND status_gestor = 'pendente'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Painel Gestor RH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.7s ease forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative flex items-start justify-center px-6 pt-24"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <div class="relative z-10 w-full max-w-6xl bg-white bg-opacity-90 backdrop-blur-md p-10 rounded-2xl shadow-2xl fade-in space-y-8">

        <!-- Header com botão de voltar -->
        <div class="flex items-center gap-5">
            <a href="?page=home" class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-3 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>
            <h1 class="text-3xl font-bold text-gray-800">📋 Aprovações do Gestor de RH</h1>
        </div>

        <!-- Tabela de solicitações -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm text-sm">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Criado por</th>
                        <th class="px-4 py-3 text-left">Data</th>
                        <th class="px-4 py-3 text-left">Ação</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-4 py-2"><?= $row['id'] ?></td>
                            <td class="px-4 py-2"><?= ucfirst($row['tipo']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['usuario'] ?? '—') ?></td>
                            <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                            <td class="px-4 py-2">
                                <a href="?page=acao_gestor&id=<?= $row['id'] ?>" class="text-blue-600 hover:underline font-medium">🔍 Ver</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
