<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$tipo = $_POST['tipo'] ?? '';
$usuario = $_SESSION['usuario'] ?? '';
$dados = json_encode($_POST, JSON_UNESCAPED_UNICODE);

$status = $tipo === 'contratacao' ? 'pendente_rh' : 'pendente_ti';

$stmt = $conn->prepare("INSERT INTO solicitacoes (usuario, tipo, dados, status, criado_em, criado_por) VALUES (?, ?, ?, ?, NOW(), ?)");
$stmt->bind_param("sssss", $usuario, $tipo, $dados, $status, $usuario);

if ($stmt->execute()) {
    header("Location: ?page=home");
    exit;
} else {
    echo "<p style='color:red; padding:20px;'>Erro ao salvar solicitação: " . $stmt->error . "</p>";
}
