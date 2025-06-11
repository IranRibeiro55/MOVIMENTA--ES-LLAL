<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['perfil'] === 'admin') {
    $permitido = true;
}

$perfil = $_SESSION['perfil'];
$id = $_GET['id'] ?? null;
$sucesso = $_GET['sucesso'] ?? null;

$permitido = false;
$dados = [];
$solicitacao = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $solicitacao = $result->fetch_assoc();

    if ($solicitacao) {
        $retornar_para = $solicitacao['retornar_para'] ?? null;
        $status_ti = $solicitacao['status_ti'] ?? null;
        $status = $solicitacao['status'] ?? null;

        $mapa = ['ti' => 'analista_ti', 'rh' => 'analista_rh'];
        $retornar_padrao = $mapa[$retornar_para] ?? $retornar_para;

        if (!empty($retornar_para) && $retornar_padrao === $perfil && $status === 'aguardando_retorno') {
            $permitido = true;
        }
        if ($perfil === 'analista_ti' && $status_ti === 'pendente' && empty($retornar_para)) {
            $permitido = true;
        }

        if ($permitido) {
            $dados = json_decode($solicitacao['dados'], true);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Solicitação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center relative px-4 pt-10"
      style="background-image: url('assets/img/Wallpapers.png');">

<div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>

<div class="relative z-10 max-w-3xl mx-auto bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl space-y-6">

    <!-- Título e botão voltar -->
    <div class="relative flex items-center justify-center mb-4">
        <div class="absolute left-0">
            <a href="?page=home" class="inline-flex items-center gap-2 text-white bg-blue-600 px-5 py-2.5 rounded-md shadow hover:bg-blue-700 transition text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>
        </div>
        <h1 class="text-2xl font-semibold text-gray-800 text-center">✏️ Editar Solicitação <?= $id ? "ID #$id" : '' ?></h1>
    </div>

    <!-- Mostrar input se não tiver ID -->
    <?php if (!$id): ?>
        <form method="GET" class="text-center space-y-4">
            <input type="hidden" name="page" value="editar">
            <label class="block text-gray-700 font-medium mb-1">Digite o ID da Solicitação:</label>
            <input type="number" name="id" required class="border p-3 rounded w-40 text-center">
            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    🔍 Buscar
                </button>
            </div>
        </form>
        <?php exit; ?>
    <?php endif; ?>

    <!-- Caso ID inválido -->
    <?php if (!$solicitacao): ?>
        <div class="text-red-700 bg-red-100 p-4 rounded shadow text-center font-medium">
            ❌ Solicitação não encontrada.
        </div>
        <?php exit; ?>
    <?php elseif (!$permitido): ?>
        <div class="text-red-700 bg-red-100 p-4 rounded shadow text-center font-medium">
            ⛔ Você não tem permissão para editar esta solicitação.
        </div>
        <?php exit; ?>
    <?php endif; ?>

    <!-- Sucesso -->
    <?php if ($sucesso): ?>
        <div class="bg-green-100 text-green-800 border border-green-300 p-4 rounded shadow text-center font-medium">
            ✅ Alterações salvas com sucesso!
        </div>
    <?php endif; ?>

    <!-- Formulário de edição -->
    <form method="POST" action="?page=salvar_edicao" class="space-y-6">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($dados as $campo => $valor): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <?= ucfirst(str_replace('_', ' ', $campo)) ?>:
                    </label>
                    <input type="text" name="<?= htmlspecialchars($campo) ?>"
                           value="<?= htmlspecialchars(is_array($valor) ? implode(', ', $valor) : $valor) ?>"
                           class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-center pt-4">
            <button type="submit"
                    class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition font-semibold flex items-center gap-2">
                💾 <span>Salvar Alterações</span>
            </button>
        </div>
    </form>
</div>
</body>
</html>
