<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o autoload do Dompdf
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';
require_once __DIR__ . '/../config/database.php';

use Dompdf\Dompdf;

// Verifica se está logado e se foi informado o ID
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header('Location: ?page=login');
    exit;
}

$id = intval($_GET['id']);
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

// Geração do HTML do PDF
$html = '<h1>📄 Relatório da Solicitação</h1>';
$html .= '<strong>ID:</strong> ' . $solicitacao['id'] . '<br>';
$html .= '<strong>Tipo:</strong> ' . ucfirst($solicitacao['tipo']) . '<br>';
$html .= '<strong>Status:</strong> ' . $solicitacao['status'] . '<br>';
$html .= '<strong>Criado por:</strong> ' . $solicitacao['criado_por'] . '<br>';
$html .= '<strong>Data de Criação:</strong> ' . $solicitacao['data_criacao'] . '<br><hr>';

foreach ($dados as $campo => $valor) {
    $label = ucwords(str_replace('_', ' ', $campo));
    if (is_array($valor)) {
        $html .= "<p><strong>$label:</strong> " . implode(', ', $valor) . "</p>";
    } else {
        $html .= "<p><strong>$label:</strong> " . htmlspecialchars($valor) . "</p>";
    }
}

// Instancia Dompdf e gera
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("solicitacao_{$id}.pdf", ['Attachment' => false]);
