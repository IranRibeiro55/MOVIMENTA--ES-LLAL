<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ?page=login');
        exit;
    }
}

require_once __DIR__ . '/../config/database.php';

$mesSelecionado = $_GET['mes'] ?? date('m');
$anoSelecionado = $_GET['ano'] ?? date('Y');

// Consulta resumo por status
$sql = "SELECT status, COUNT(*) as total 
        FROM solicitacoes 
        WHERE MONTH(criado_em) = ? AND YEAR(criado_em) = ?
        GROUP BY status";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $mesSelecionado, $anoSelecionado);
$stmt->execute();
$resumo = $stmt->get_result();

$dadosGrafico = [];
while ($row = $resumo->fetch_assoc()) {
    $dadosGrafico[$row['status']] = $row['total'];
}

$labels = json_encode(array_keys($dadosGrafico));
$valores = json_encode(array_values($dadosGrafico));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<body class="min-h-screen bg-cover bg-center relative px-4 pt-10"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>

    <div class="relative z-10 max-w-5xl mx-auto w-full bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl fade-in space-y-8">

        <!-- Cabeçalho com botão à esquerda e título centralizado -->
        <div class="relative flex items-center justify-center">
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
                📊 Dashboard de Solicitações
            </h1>
        </div>

        <!-- Filtro -->
        <form method="get" class="flex flex-wrap gap-4 items-end justify-center">
            <input type="hidden" name="page" value="dashboard" />

            <?php
            $meses = [
                '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março',
                '04' => 'Abril', '05' => 'Maio', '06' => 'Junho',
                '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro',
                '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
            ];
            ?>

            <label class="flex flex-col text-sm font-semibold text-gray-700">
                Mês:
                <select name="mes" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php
                    foreach ($meses as $value => $nome) {
                        $selected = $value == $mesSelecionado ? 'selected' : '';
                        echo "<option value='$value' $selected>$nome</option>";
                    }
                    ?>
                </select>
            </label>

            <label class="flex flex-col text-sm font-semibold text-gray-700">
                Ano:
                <select name="ano" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php
                    for ($a = 2024; $a <= date('Y'); $a++) {
                        $selected = $a == $anoSelecionado ? 'selected' : '';
                        echo "<option value='$a' $selected>$a</option>";
                    }
                    ?>
                </select>
            </label>

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 shadow transition font-semibold">
                🔍 Filtrar
            </button>
        </form>

        <!-- Gráfico -->
        <div class="bg-white rounded-md shadow p-4">
            <canvas id="grafico" height="120"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('grafico').getContext('2d');
        const grafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Solicitações',
                    data: <?= $valores ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
