<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$usuario = $_SESSION['usuario'] ?? '';
$perfil = $_SESSION['perfil'] ?? '';

if ($perfil !== 'contratacao') {
    echo "<p style='color:red; padding:20px;'>⛔ Acesso negado.</p>";
    exit;
}

// Coleta e valida os dados do POST
$setor = $_POST['setor'] ?? '';
$funcao = $_POST['funcao'] ?? '';
$notebook = isset($_POST['precisa_notebook']) ? 1 : 0;
$computador = isset($_POST['precisa_computador']) ? 1 : 0;
$email = isset($_POST['precisa_email']) ? 1 : 0;
$data_abertura = $_POST['data_abertura'] ?? '';
$previsao_entrada = $_POST['previsao_entrada'] ?? '';

// Validação de data
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_abertura) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $previsao_entrada)) {
    echo "<p style='color:red; padding:20px;'>❌ Formato de data inválido.</p>";
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO vagas_previstas (
        setor, funcao, precisa_notebook, precisa_computador, precisa_email, data_abertura, previsao_entrada, criado_por, criado_em
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");
$stmt->bind_param(
    "ssiiisss",
    $setor,
    $funcao,
    $notebook,
    $computador,
    $email,
    $data_abertura,
    $previsao_entrada,
    $usuario
);

if ($stmt->execute()) {
    header('Location: ?page=home_vaga');
    exit;
} else {
    echo "<p style='color:red; padding:20px;'>Erro ao salvar: " . $stmt->error . "</p>";
}
?>
