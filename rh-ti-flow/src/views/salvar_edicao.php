<?php
// Inicia a sessão se ainda não tiver sido iniciada
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/log.php';

// Valida se veio um ID e se o usuário está autenticado
$id = $_POST['id'] ?? null;

if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil']) || !$id) {
    echo "Acesso inválido.";
    exit;
}

// Pega dados do usuário atual
$usuario = $_SESSION['usuario'];
$perfil = $_SESSION['perfil'];

// Pega os dados do formulário, exceto o ID
$dados = $_POST;
unset($dados['id']);

$dados_json = json_encode($dados, JSON_UNESCAPED_UNICODE);

// Busca no banco quem devolveu essa solicitação (RH ou TI)
$stmt = $conn->prepare("SELECT retornar_para FROM solicitacoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$solicitacao = $result->fetch_assoc();

$retornado_para = $solicitacao['retornar_para'] ?? null;

// ======================
// 🔁 Lógica de retorno
// ======================

// Lógica atualizada para TI devolvendo para o Gestor RH
if ($perfil === 'analista_ti' && in_array($retornado_para, ['ti', 'analista_ti'])) {
    $status = 'pendente_gestor';           // Fluxo volta pro GESTOR
    $status_ti = 'aprovado';               // TI já revisou
    $status_gestor = 'pendente';           // GESTOR precisa decidir
    $retornar_para = null;
}

// 🧠 Se foi devolvido pro RH e ele está respondendo
elseif ($perfil === 'analista_rh' && $retornado_para === 'rh') {
    $status = 'pendente';
    $status_ti = 'pendente';         // TI revisa de novo se quiser (opcional)
    $status_gestor = 'pendente';     // volta pro gestor RH obrigatoriamente
    $retornar_para = null;
}

// 🧯 Fallback de segurança (não deveria cair aqui normalmente)
else {
    $status = 'pendente';
    $status_ti = 'pendente';
    $status_gestor = 'pendente';
    $retornar_para = null;
}

// ==========================
// 💾 Atualiza a solicitação
// ==========================
$update = $conn->prepare("
    UPDATE solicitacoes 
    SET dados = ?, status = ?, status_ti = ?, status_gestor = ?, retornar_para = ?, ultima_atualizacao = NOW()
    WHERE id = ?
");

$update->bind_param("sssssi", $dados_json, $status, $status_ti, $status_gestor, $retornar_para, $id);

if ($update->execute()) {
    registrar_log($conn, $usuario, "edicao_solicitacao", $id);
    header('Location: ?page=home');
    exit;
} else {
    echo "Erro ao atualizar: " . $update->error;
}
