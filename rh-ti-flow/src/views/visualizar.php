<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID da solicitação não informado.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$solicitacao = $result->fetch_assoc();

if (!$solicitacao) {
    echo "Solicitação não encontrada.";
    exit;
}

$dados = json_decode($solicitacao['dados'], true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Solicitação</title>
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
<body class="min-h-screen bg-cover bg-center relative flex items-start justify-center px-4 pt-10" style="background-image: url('assets/img/Wallpapers.png');">
    <div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>

    <div class="relative z-10 max-w-5xl w-full bg-white bg-opacity-90 backdrop-blur-md p-10 rounded-2xl shadow-2xl fade-in space-y-8">

        <!-- Topo -->
        <div class="flex items-center justify-between">
      <a href="?page=home" class="inline-flex items-center gap-2 text-white bg-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold text-base">
    <span class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
        </svg>
        Voltar
    </span>
</a>
            <h1 class="text-2xl font-bold text-gray-900 text-center flex-1 -ml-16">🔍 Solicitação #<?= $id ?></h1>
            <span class="w-24"></span>
        </div>

        <!-- Infos principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-base text-gray-800">
            <p><strong>Tipo:</strong> <?= htmlspecialchars($solicitacao['tipo']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($solicitacao['status']) ?></p>
            <p><strong>Criado por:</strong> <?= $solicitacao['criado_por'] ?: '—' ?></p>
            <p><strong>Data:</strong> <?= $solicitacao['data_criacao'] ?></p>
        </div>

        <hr class="my-4">

        <!-- Detalhes -->
        <h2 class="text-xl font-semibold text-gray-800">📋 Dados da Solicitação</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-base text-gray-800">
            <?php foreach ($dados as $campo => $valor): ?>
                <div>
                    <strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong>
                    <span><?= is_array($valor) ? htmlspecialchars(implode(', ', $valor)) : htmlspecialchars($valor) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Comentários -->
        <?php if (!empty($solicitacao['comentario_ti'])): ?>
            <div class="mt-4 bg-blue-100 p-4 rounded">
                <strong>Comentário TI:</strong><br>
                <?= nl2br(htmlspecialchars($solicitacao['comentario_ti'])) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($solicitacao['comentario_gestor'])): ?>
            <div class="mt-4 bg-yellow-100 p-4 rounded">
                <strong>Comentário Gestor:</strong><br>
                <?= nl2br(htmlspecialchars($solicitacao['comentario_gestor'])) ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
