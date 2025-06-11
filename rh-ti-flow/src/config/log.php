<?php
function registrar_log($conn, $usuario, $acao, $solicitacao_id) {
    $stmt = $conn->prepare("INSERT INTO logs (usuario, acao, solicitacao_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $usuario, $acao, $solicitacao_id);
    $stmt->execute();
}
