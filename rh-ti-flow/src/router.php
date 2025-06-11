<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require __DIR__ . '/views/login.php';
        break;
    case 'home':
        require __DIR__ . '/views/home.php';
        break;
    case 'nova_solicitacao':
        require __DIR__ . '/views/nova_solicitacao.php';
        break;
    case 'salvar_solicitacao':
        require __DIR__ . '/views/salvar_solicitacao.php';
        break;
    case 'painel_ti':
        require __DIR__ . '/views/painel_ti.php';
        break;
    case 'acao_ti':
        require __DIR__ . '/views/acao_ti.php';
        break;
    case 'painel_gestor':
        require __DIR__ . '/views/painel_gestor.php';
        break;
    case 'acao_gestor':
        require __DIR__ . '/views/acao_gestor.php';
        break;
    case 'editar':
        require __DIR__ . '/views/editar.php';
        break;
    case 'salvar_edicao':
        require __DIR__ . '/views/salvar_edicao.php';
        break;
    case 'gerar_pdf':
        require __DIR__ . '/views/gerar_pdf.php';
        break;
    case 'logs':
        require __DIR__ . '/views/logs.php';
        break;
    case 'dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;
    case 'historico':
        require __DIR__ . '/views/historico.php';
        break;
    case 'visualizar':
        require __DIR__ . '/views/visualizar.php';
        break;
    case 'pendencias':
        require __DIR__ . '/views/pendencias.php';
        break;
    case 'listar_vagas':
        require __DIR__ . '/views/listar_vagas.php';
        break;
    case 'nova_vaga':
        require __DIR__ . '/views/nova_vaga.php';
        break;
    case 'editar_vaga':
        require __DIR__ . '/views/editar_vaga.php';
        break;
    case 'salvar_vaga':
        require __DIR__ . '/views/salvar_vaga.php';
        break;
    case 'home_vaga':
        require __DIR__ . '/views/home_vaga.php';
        break;
    case 'cadastrar_usuario':
        require __DIR__ . '/views/cadastrar_usuario.php';
        break;
    case 'salvar_usuario':
        require __DIR__ . '/views/salvar_usuario.php';
        break;
    case 'trocar_senha':
        require __DIR__ . '/views/trocar_senha.php';
        break;
    case 'redefinir_senha':
        require __DIR__ . '/views/redefinir_senha.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ?page=login');
        exit;
    default:
        echo "<h1 style='padding:20px; color:red;'>Página não encontrada!</h1>";
        break;
}
