<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ?page=login');
    exit;
}

$stmt = $conn->query("SELECT * FROM logs ORDER BY data_hora DESC");
$logs = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-6 flex items-center">
            🔍 Logs do Sistema
        </h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Usuário</th>
                        <th class="py-2 px-4 border">Ação</th>
                        <th class="py-2 px-4 border">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr class="border-t">
                            <td class="py-2 px-4 text-center"><?= $log['id'] ?></td>
                            <td class="py-2 px-4 text-center"><?= htmlspecialchars($log['usuario']) ?></td>
                            <td class="py-2 px-4 text-center"><?= htmlspecialchars($log['acao']) ?></td>
                            <td class="py-2 px-4 text-center"><?= date('d/m/Y H:i', strtotime($log['data_hora'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="?page=home" class="text-blue-600 hover:underline">&larr; Voltar</a>
        </div>
    </div>
</body>
</html>