<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$usuarioLogado = $_SESSION['usuario'] ?? '';

$stmt = $conn->prepare("SELECT perfil FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuarioLogado);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$perfil = $userData['perfil'] ?? '';

if (!in_array($perfil, ['contratacao', 'gestor_rh'])) {
    echo "<p style='color:red'>⛔ Acesso negado. Apenas contratação ou gestor RH podem editar.</p>";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p style='color:red'>ID não informado.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM vagas_previstas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$vaga = $result->fetch_assoc();

if (!$vaga) {
    echo "<p style='color:red'>Vaga não encontrada.</p>";
    exit;
}

// Converte de dd/mm/yyyy para yyyy-mm-dd
function formatarData($data) {
    if (empty($data)) return false;

    // Verifica se já está em formato yyyy-mm-dd
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        $partes = explode('-', $data);
        if (checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0]) && (int)$partes[0] >= 1000) {
            return $data;
        }
    }

    // Se estiver em formato dd/mm/yyyy
    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data)) {
        [$dia, $mes, $ano] = explode('/', $data);
        if (checkdate((int)$mes, (int)$dia, (int)$ano) && (int)$ano >= 1000) {
            return sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
        }
    }

    return false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setor = $_POST['setor'] ?? '';
    $funcao = $_POST['funcao'] ?? '';
    $precisa_notebook = isset($_POST['notebook']) ? 1 : 0;
    $precisa_computador = isset($_POST['computador']) ? 1 : 0;
    $precisa_email = isset($_POST['email']) ? 1 : 0;

    $data_abertura_input = $_POST['data_abertura'] ?? '';
    $previsao_entrada_input = $_POST['previsao_entrada'] ?? '';

    $data_abertura = formatarData($data_abertura_input);
    $previsao_entrada = formatarData($previsao_entrada_input);

    $observacoes = $_POST['observacoes'] ?? '';

    if (!$data_abertura || !$previsao_entrada) {
        echo "<p style='color:red'>⚠️ Data inválida! Use o formato <strong>dd/mm/aaaa</strong> e um ano válido (≥ 1000).</p>";
    } else {
        $stmt = $conn->prepare("UPDATE vagas_previstas SET setor=?, funcao=?, precisa_notebook=?, precisa_computador=?, precisa_email=?, data_abertura=?, previsao_entrada=?, observacoes=? WHERE id=?");
        $stmt->bind_param("ssiiiissi", $setor, $funcao, $precisa_notebook, $precisa_computador, $precisa_email, $data_abertura, $previsao_entrada, $observacoes, $id);
        $stmt->execute();

        echo "<script>alert('✅ Vaga atualizada com sucesso!'); window.location='?page=listar_vagas';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Vaga Prevista</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow fade-in">
        <h1 class="text-2xl font-bold mb-4">✏️ Editar Vaga Prevista</h1>
        <form method="POST">
            <label class="block font-bold mb-2" for="setor">Setor:</label>
            <input type="text" name="setor" id="setor" class="w-full border border-gray-300 p-2 mb-4 rounded"
                value="<?php echo htmlspecialchars($vaga['setor'] ?? '', ENT_QUOTES); ?>">

            <label class="block font-bold mb-2" for="funcao">Função:</label>
            <input type="text" name="funcao" id="funcao" class="w-full border border-gray-300 p-2 mb-4 rounded"
                value="<?php echo htmlspecialchars($vaga['funcao'] ?? '', ENT_QUOTES); ?>">

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="notebook" class="mr-2" <?php echo $vaga['precisa_notebook'] ? 'checked' : ''; ?>>
                    Precisa de notebook
                </label>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="computador" class="mr-2" <?php echo $vaga['precisa_computador'] ? 'checked' : ''; ?>>
                    Precisa de computador
                </label>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="email" class="mr-2" <?php echo $vaga['precisa_email'] ? 'checked' : ''; ?>>
                    Precisa de e-mail
                </label>
            </div>

            <label class="block font-bold mb-2" for="data_abertura">Data de Abertura:</label>
            <input type="date" name="data_abertura" id="data_abertura"
                class="w-full border border-gray-300 p-2 mb-4 rounded"
                value="<?php echo htmlspecialchars($vaga['data_abertura'] ?? '', ENT_QUOTES); ?>">

            <label class="block font-bold mb-2" for="previsao_entrada">Previsão de Entrada:</label>
            <input type="date" name="previsao_entrada" id="previsao_entrada"
                class="w-full border border-gray-300 p-2 mb-4 rounded"
                value="<?php echo htmlspecialchars($vaga['previsao_entrada'] ?? '', ENT_QUOTES); ?>">

            <label class="block font-bold mb-2" for="observacoes">Observações:</label>
            <textarea name="observacoes" id="observacoes"
                class="w-full border border-gray-300 p-2 rounded"><?php echo htmlspecialchars(is_string($vaga['observacoes']) ? $vaga['observacoes'] : '', ENT_QUOTES); ?></textarea>

            <button type="submit"
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-all">
                💾 Atualizar Vaga
            </button>
        </form>
        <a href="?page=listar_vagas" class="mt-4 block text-blue-600 hover:underline">← Voltar para Lista</a>
    </div>
</body>
</html>
