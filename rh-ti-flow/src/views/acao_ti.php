<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'analista_ti') {
    header('Location: ?page=login');
    exit;
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $comentario = $_POST['comentario'] ?? '';

    if ($acao === 'aprovar') {
        $status_ti = 'aprovado';
        $status_gestor = 'pendente';

        $stmt = $conn->prepare("UPDATE solicitacoes SET status_ti = ?, status_gestor = ?, comentario_ti = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status_ti, $status_gestor, $comentario, $id);

    } elseif ($acao === 'recusar') {
        $status_ti = 'recusado';
        $status_gestor = 'cancelado';

        $stmt = $conn->prepare("UPDATE solicitacoes SET status_ti = ?, status_gestor = ?, comentario_ti = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status_ti, $status_gestor, $comentario, $id);

    } else {
        header("Location: ?page=painel_ti");
        exit;
    }

    $stmt->execute();
    header("Location: ?page=painel_ti");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Análise TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        .btn-acao {
            transition: all 0.3s ease;
        }

        .btn-acao:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-20"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <div class="relative z-10 max-w-2xl mx-auto w-full bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl fade-in space-y-8">

        <!-- Cabeçalho com botão à esquerda e título centralizado -->
        <div class="relative flex items-center justify-center mb-4">
            <!-- Botão na esquerda absoluta -->
            <div class="absolute left-0">
                <a href="?page=painel_ti" class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-2.5 rounded-md shadow hover:bg-blue-700 transition text-[15px] font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Voltar
                </a>
            </div>

            <!-- Título centralizado -->
            <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                🔍 <span>Analisar Solicitação ID #<?= $id ?></span>
            </h1>
        </div>

        <!-- Dados organizados -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm md:text-base text-gray-800 mt-2">
            <?php foreach ($dados as $campo => $valor): ?>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500">
                        <?= ucfirst(str_replace('_', ' ', $campo)) ?>:
                    </span>
                    <span class="font-medium break-words">
                        <?= is_array($valor) ? implode(', ', $valor) : htmlspecialchars($valor) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulário -->
        <form method="POST" class="space-y-6 mt-6">
            <label class="block text-sm font-medium text-gray-700">Observações:</label>
            <textarea name="comentario" placeholder="Comentários (opcional)" rows="3"
                      class="w-full p-3 border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 text-base leading-relaxed"
            ></textarea>

            <div class="flex flex-col md:flex-row gap-4">
                <button type="submit" name="acao" value="aprovar"
                        class="btn-acao flex-1 bg-green-600 text-white py-3 rounded-md hover:bg-green-700 transition text-base font-semibold">
                    ✅ Aprovar
                </button>
                <button type="submit" name="acao" value="recusar"
                        class="btn-acao flex-1 bg-red-600 text-white py-3 rounded-md hover:bg-red-700 transition text-base font-semibold">
                    ❌ Recusar
                </button>
            </div>
        </form>

    </div>
</body>
</html>
