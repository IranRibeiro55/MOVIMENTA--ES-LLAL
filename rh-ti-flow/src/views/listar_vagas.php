<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$usuario = $_SESSION['usuario'] ?? '';
$perfil = $_SESSION['perfil'] ?? '';

// Permitir acesso apenas a quem pode visualizar
$perfis_autorizados = ['analista_rh', 'analista_ti', 'gestor_rh', 'contratacao'];

if (!in_array($perfil, $perfis_autorizados)) {
    echo "<p style='color:red; padding:20px;'>⛔ Acesso negado.</p>";
    exit;
}

// Busca todas as vagas
$stmt = $conn->prepare("SELECT * FROM vagas_previstas ORDER BY data_abertura DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vagas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">📄 Lista de Vagas Previstas</h1>

        <?php if ($result->num_rows === 0): ?>
            <p class="text-gray-600">Nenhuma vaga encontrada.</p>
        <?php else: ?>
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Setor</th>
                        <th class="px-4 py-2 border">Função</th>
                        <th class="px-4 py-2 border">Notebook</th>
                        <th class="px-4 py-2 border">Computador</th>
                        <th class="px-4 py-2 border">E-mail</th>
                        <th class="px-4 py-2 border">Abertura</th>
                        <th class="px-4 py-2 border">Entrada</th>
                        <th class="px-4 py-2 border">Criado por</th>
                        <th class="px-4 py-2 border">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-4 py-2 text-center"><?= $row['id'] ?></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($row['setor']) ?></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($row['funcao']) ?></td>
                            <td class="px-4 py-2 text-center"><?= $row['precisa_notebook'] ? '✅' : '❌' ?></td>
                            <td class="px-4 py-2 text-center"><?= $row['precisa_computador'] ? '✅' : '❌' ?></td>
                            <td class="px-4 py-2 text-center"><?= $row['precisa_email'] ? '✅' : '❌' ?></td>
                            <td class="px-4 py-2 text-center"><?= date('d/m/Y', strtotime($row['data_abertura'])) ?></td>
                            <td class="px-4 py-2 text-center"><?= date('d/m/Y', strtotime($row['previsao_entrada'])) ?></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($row['criado_por']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <?php if ($perfil === 'contratacao' || $perfil === 'gestor_rh'): ?>
                                    <a href="?page=editar_vaga&id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">✏️ Editar</a>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="mt-6">
            <a href="?page=home" class="text-blue-600 hover:underline">← Voltar</a>
        </div>
    </div>
</body>
</html>
