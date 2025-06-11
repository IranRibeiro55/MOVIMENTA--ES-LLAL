<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'gestor_rh') {
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
        $novo_status = 'aprovado';
        $retornar_para = null;
    } elseif ($acao === 'recusar') {
        $novo_status = 'recusado';
        $retornar_para = null;
    } elseif ($acao === 'devolver_rh') {
        $novo_status = 'aguardando_retorno';
        $retornar_para = 'analista_rh';
    } elseif ($acao === 'devolver_ti') {
        $novo_status = 'aguardando_retorno';
        $retornar_para = 'analista_ti';
    } else {
        $novo_status = $solicitacao['status'];
        $retornar_para = null;
    }

    $valores_validos = ['analista_rh', 'analista_ti', null];
    if (!in_array($retornar_para, $valores_validos, true)) {
        die("Valor inválido para retornar_para.");
    }

    $status_gestor = $novo_status;
    $stmt = $conn->prepare("UPDATE solicitacoes SET status = ?, retornar_para = ?, comentario_gestor = ?, status_gestor = ? WHERE id = ?");
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $novo_status, $retornar_para, $comentario, $status_gestor, $id);

    if (!$stmt->execute()) {
        die("Erro ao executar atualização: " . $stmt->error);
    }

    header("Location: ?page=painel_gestor");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Análise Gestor RH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center relative flex items-start justify-center px-6 pt-24"
      style="background-image: url('assets/img/Wallpapers.png');">

    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <div class="relative z-10 w-full max-w-3xl bg-white bg-opacity-90 backdrop-blur-md p-10 rounded-2xl shadow-2xl fade-in space-y-6 text-base">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="?page=painel_gestor" class="inline-flex items-center gap-2 text-white bg-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>
            <h1 class="text-2xl font-bold text-gray-800">🧐 Analisar Solicitação ID #<?= $id ?></h1>
        </div>

        <!-- Dados da Solicitação -->
        <div class="space-y-2">
            <?php foreach ($dados as $campo => $valor): ?>
                <p><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong>
                    <?= is_array($valor) ? implode(', ', array_map('htmlspecialchars', $valor)) : htmlspecialchars($valor) ?>
                </p>
            <?php endforeach; ?>
        </div>

        <!-- Formulário de Ação -->
        <form method="POST" class="space-y-4">
            <textarea name="comentario" placeholder="Comentário (opcional)" class="w-full p-3 border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
            <div class="flex flex-wrap gap-3">
                <button type="submit" name="acao" value="aprovar" class="flex-1 bg-green-600 text-white py-3 rounded hover:bg-green-700 transition shadow">✅ Aprovar</button>
                <button type="submit" name="acao" value="recusar" class="flex-1 bg-red-600 text-white py-3 rounded hover:bg-red-700 transition shadow">❌ Recusar</button>
                <button type="submit" name="acao" value="devolver_ti" class="flex-1 bg-yellow-500 text-white py-3 rounded hover:bg-yellow-600 transition shadow">↩️ Devolver ao TI</button>
                <button type="submit" name="acao" value="devolver_rh" class="flex-1 bg-purple-600 text-white py-3 rounded hover:bg-purple-700 transition shadow">↩️ Devolver ao RH</button>
            </div>
        </form>

    </div>
</body>
</html>
