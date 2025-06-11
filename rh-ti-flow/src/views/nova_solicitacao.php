<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'analista_rh') {
    header('Location: ?page=login');
    exit;
}

$tipo = $_GET['tipo'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Nova Solicitação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .fade-in {
            animation: fadeIn 0.7s ease forwards;
        }
    </style>
</head>
<body
    class="min-h-screen bg-cover bg-center relative flex items-start justify-center px-6 pt-16"
    style="background-image: url('assets/img/Wallpapers.png');"
>
    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <div class="relative z-10 max-w-4xl w-full bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-3xl shadow-2xl fade-in space-y-8">

        <!-- Topo com botão de voltar e título -->
        <div class="flex items-center justify-between">
            <a href="?page=home" class="inline-flex items-center gap-2 text-white bg-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition font-medium text-base select-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar
            </a>
            <h1 class="text-2xl font-bold text-gray-900 text-center flex-1 -ml-16">📄 Nova Solicitação</h1>
            <span class="w-24"></span> <!-- espaçador para manter centralização visual -->
        </div>

        <!-- Formulário de tipo -->
        <form method="get" action="" class="space-y-4">
            <input type="hidden" name="page" value="nova_solicitacao" />
            <div>
                <label for="tipo" class="block mb-1 font-semibold text-gray-700 text-sm">Tipo de Solicitação:</label>
                <select id="tipo" name="tipo" required
                        class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500"
                        onchange="this.form.submit()">
                    <option value="">-- Selecione --</option>
                    <option value="contratacao" <?= $tipo === 'contratacao' ? 'selected' : '' ?>>Contratação</option>
                    <option value="desligamento" <?= $tipo === 'desligamento' ? 'selected' : '' ?>>Desligamento</option>
                    <option value="afastamento" <?= $tipo === 'afastamento' ? 'selected' : '' ?>>Afastamento / Retorno / Férias</option>
                    <option value="promocao" <?= $tipo === 'promocao' ? 'selected' : '' ?>>Promoção / Alteração</option>
                    <option value="transferencia" <?= $tipo === 'transferencia' ? 'selected' : '' ?>>Transferência de Loja</option>
                </select>
            </div>
        </form>

        <!-- Inclusão do formulário de acordo com tipo -->
        <div class="mt-4">
            <?php
            if ($tipo) {
                $tiposValidos = ['contratacao', 'desligamento', 'afastamento', 'promocao', 'transferencia'];
                if (in_array($tipo, $tiposValidos)) {
                    $formPath = __DIR__ . "/forms/{$tipo}.php";
                    if (file_exists($formPath)) {
                        include $formPath;
                    } else {
                        echo "<p class='text-red-600 font-semibold'>❌ Formulário '{$tipo}' não encontrado na pasta /forms.</p>";
                    }
                } else {
                    echo "<p class='text-red-600 font-semibold'>❌ Tipo de solicitação inválido.</p>";
                }
            }
            ?>
        </div>

    </div>
</body>
</html>
