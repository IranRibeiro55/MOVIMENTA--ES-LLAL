<?php
require_once __DIR__ . '/../config/database.php';

$mesSelecionado = $_GET['mes'] ?? date('m');
$anoSelecionado = $_GET['ano'] ?? date('Y');
$statusSelecionado = $_GET['status'] ?? '';

$meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];

$statusLabels = [
    '' => 'Todos',
    'pendente_rh' => 'Pendente RH',
    'pendente_ti' => 'Pendente TI',
    'devolvido_rh' => 'Devolvido RH',
    'devolvido_ti' => 'Devolvido TI',
    'aprovado' => 'Aprovado',
    'recusado' => 'Recusado',
    'indefinido' => 'Indefinido'
];

$query = "SELECT * FROM solicitacoes WHERE MONTH(criado_em) = ? AND YEAR(criado_em) = ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $mesSelecionado, $anoSelecionado);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Solicitações</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center relative flex items-center justify-center px-4"
      style="background-image: url('assets/img/Wallpapers.png');">

<div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

<div class="relative z-10 max-w-6xl w-full bg-white bg-opacity-90 backdrop-blur-md p-8 md:p-10 rounded-2xl shadow-2xl space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <a href="?page=home" class="inline-flex items-center gap-2 text-white bg-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar para Home
        </a>

        <form method="get" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="page" value="historico">

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Mês:</label>
                <select name="mes" class="border border-gray-300 p-2 rounded w-40">
                    <?php foreach ($meses as $num => $nome): ?>
                        <option value="<?= $num ?>" <?= ($mesSelecionado == $num) ? 'selected' : '' ?>><?= $nome ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Ano:</label>
                <select name="ano" class="border border-gray-300 p-2 rounded w-40">
                    <?php for ($y = date('Y'); $y >= 2023; $y--): ?>
                        <option value="<?= $y ?>" <?= ($anoSelecionado == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Status:</label>
                <select name="status" class="border border-gray-300 p-2 rounded w-48">
                    <?php foreach ($statusLabels as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($statusSelecionado === $key) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-5">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition shadow flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <h1 class="text-3xl font-bold text-gray-800">📁 Histórico de Solicitações</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm mt-4 rounded overflow-hidden shadow">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Tipo</th>
                    <th class="px-4 py-2">Criado por</th>
                    <th class="px-4 py-2">Data</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Ação</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                if ($row['status_ti'] === 'aprovado' && $row['status_gestor'] === 'aprovado') {
                    $status = 'aprovado';
                } elseif ($row['status_ti'] === 'recusado' || $row['status_gestor'] === 'recusado') {
                    $status = 'recusado';
                } elseif (in_array($row['retornar_para'], ['rh', 'analista_rh'])) {
                    $status = 'devolvido_rh';
                } elseif (in_array($row['retornar_para'], ['ti', 'analista_ti'])) {
                    $status = 'devolvido_ti';
                } elseif ($row['status_ti'] === 'pendente') {
                    $status = 'pendente_ti';
                } elseif ($row['status_gestor'] === 'pendente') {
                    $status = 'pendente_rh';
                } else {
                    $status = 'indefinido';
                }

                if ($statusSelecionado && $statusSelecionado !== $status) continue;

                $cores = [
                    'pendente_rh' => 'bg-gray-400',
                    'pendente_ti' => 'bg-yellow-400',
                    'devolvido_rh' => 'bg-pink-500',
                    'devolvido_ti' => 'bg-purple-500',
                    'aprovado' => 'bg-green-500',
                    'recusado' => 'bg-red-500',
                    'indefinido' => 'bg-gray-200 text-black'
                ];
                ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-center"><?= $row['id'] ?></td>
                    <td class="px-4 py-2 text-center"><?= htmlspecialchars($row['tipo']) ?></td>
                    <td class="px-4 py-2 text-center"><?= htmlspecialchars($row['criado_por'] ?? '---') ?></td>
                    <td class="px-4 py-2 text-center"><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                    <td class="px-4 py-2 text-center">
                        <span class="text-white px-2 py-1 rounded <?= $cores[$status] ?>">
                            <?= $statusLabels[$status] ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="?page=visualizar&id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">🔍 Ver</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
