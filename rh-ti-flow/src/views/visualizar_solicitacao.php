<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header('Location: ?page=login');
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$solicitacao = $result->fetch_assoc();

if (!$solicitacao) {
    echo "<p>Solicitação não encontrada.</p>";
    exit;
}

$dados = json_decode($solicitacao['dados'], true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Solicitação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">📄 Detalhes da Solicitação ID #<?= $id ?></h1>

        <div class="grid grid-cols-2 gap-4">
            <p><strong>Tipo:</strong> <?= htmlspecialchars($solicitacao['tipo']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($solicitacao['status'] ?? 'Indefinido') ?></p>
            <p><strong>Criado por:</strong> <?= htmlspecialchars($solicitacao['criado_por'] ?? '---') ?></p>
            <p><strong>Data de Criação:</strong> <?= htmlspecialchars($solicitacao['data_criacao']) ?></p>
        </div>

        <hr class="my-4">

        <h2 class="text-lg font-semibold mb-2">📋 Dados da Solicitação</h2>
        <div class="space-y-2">
            <?php foreach ($dados as $campo => $valor): ?>
                <div>
                    <strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong>
                    <?php if (is_array($valor)): ?>
                        <?= htmlspecialchars(implode(', ', $valor)) ?>
                    <?php else: ?>
                        <?= htmlspecialchars($valor) ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-6">
            <a href="?page=Home" class="text-blue-600 hover:underline">⬅ Voltar ao Histórico</a>
        </div>
    </div>
</body>
</html>
