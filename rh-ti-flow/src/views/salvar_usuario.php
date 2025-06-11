<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$perfil = $_SESSION['perfil'] ?? '';

if ($perfil !== 'analista_ti') {
    echo "<p style='color:red; padding:20px;'>⛔ Acesso negado.</p>";
    exit;
}

// Captura e valida dados do POST
$nome = trim($_POST['nome'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';
$perfilNovo = $_POST['perfil'] ?? '';
$ativo = 1;
$primeiro_acesso = 1; // ✅ Garante que o usuário será forçado a trocar a senha no primeiro login

if (!$nome || !$usuario || !$senha || !$perfilNovo) {
    echo "<p style='color:red; padding:20px;'>❌ Todos os campos são obrigatórios.</p>";
    exit;
}

// Verifica se o login já existe
$verifica = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$verifica->bind_param("s", $usuario);
$verifica->execute();
$res = $verifica->get_result();
if ($res->num_rows > 0) {
    echo "<p style='color:red; padding:20px;'>❌ Nome de usuário já existe.</p>";
    exit;
}

// Criptografa a senha
$hash = password_hash($senha, PASSWORD_BCRYPT);

// ✅ Agora inclui o campo 'primeiro_acesso'
$stmt = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha, perfil, ativo, criado_em, primeiro_acesso) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
$stmt->bind_param("ssssii", $nome, $usuario, $hash, $perfilNovo, $ativo, $primeiro_acesso);

if ($stmt->execute()) {
    header("Location: ?page=home");
    exit;
} else {
    echo "<p style='color:red; padding:20px;'>Erro ao cadastrar usuário: " . $stmt->error . "</p>";
}
