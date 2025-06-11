<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$setor_param = $_GET['setor'] ?? '';
$usuario = $_SESSION['usuario'] ?? '';
$perfil = $_SESSION['perfil'] ?? '';

// Validação do setor recebido
if (!in_array($setor_param, ['ti', 'rh'])) {
    echo "<p style='color:red; padding:20px;'>Setor inválido.</p>";
    exit;
}

// Converte para perfil interno
$setor = $setor_param === 'ti' ? 'analista_ti' : 'analista_rh';

// Somente solicitações devolvidas para este setor
$stmt = $conn->prepare("
    SELECT * FROM solicitacoes 
    WHERE retornar_para = ? 
    AND status = 'aguardando_retorno'
    ORDER BY criado_em DESC
");
$stmt->bind_param("s", $setor);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Pendências do Setor <?= strtoupper(htmlspecialchars($setor_param)) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.7s ease forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-20"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <div class="relative z-10 max-w-4xl w-full mx-auto bg-white bg-opacity-90 backdrop-blur-md p-10 rounded-2xl shadow-2xl fade-in space-y-6">

        <!-- Cabeçalho com botão à esquerda e título centralizado -->
        <div class="relative flex items-center justify-center mb-4">
            <div class="absolute left-0">
                <a href="?page=home"
                   class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-2.5 rounded-md shadow hover:bg-blue-700 transition text-[15px] font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Voltar
                </a>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 text-center">
                📌 Pendências do Setor <span class="uppercase"><?= htmlspecialchars($setor_param) ?></span>
            </h1>
        </div>

        <!-- Lista de pendências -->
        <?php if ($result->num_rows === 0): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow text-center">
                ✅ Nenhuma pendência devolvida para este setor.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-md mt-4 shadow">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold">
                        <tr>
                            <th class="px-4 py-3 border">ID</th>
                            <th class="px-4 py-3 border">Tipo</th>
                            <th class="px-4 py-3 border">Data</th>
                            <th class="px-4 py-3 border">Status Atual</th>
                            <th class="px-4 py-3 border">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="text-center text-gray-700 text-sm">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2"><?= $row['id'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['tipo']) ?></td>
                                <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                                <td class="px-4 py-2 uppercase"><?= htmlspecialchars($row['status'] ?? 'indefinido') ?> / <?= htmlspecialchars($row['retornar_para'] ?? '-') ?></td>
                                <td class="px-4 py-2">
                                    <a href="?page=editar&id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">✏️ Editar</a>
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
