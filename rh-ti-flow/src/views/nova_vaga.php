<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';

$usuario = $_SESSION['usuario'] ?? '';
$perfil = $_SESSION['perfil'] ?? '';

if ($perfil !== 'contratacao') {
    echo "<p class='text-red-600 p-4'>⛔ Acesso negado. Somente setor de contratação pode cadastrar vagas.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Vaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">➕ Nova Vaga Prevista</h1>

        <form method="POST" action="?page=salvar_vaga" class="space-y-4">
            <div>
                <label for="setor" class="block font-medium">Setor</label>
                <input type="text" name="setor" required class="w-full border p-2 rounded">
            </div>

            <div>
                <label for="funcao" class="block font-medium">Função</label>
                <input type="text" name="funcao" required class="w-full border p-2 rounded">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <label><input type="checkbox" name="precisa_notebook"> Notebook</label>
                <label><input type="checkbox" name="precisa_computador"> Computador</label>
                <label><input type="checkbox" name="precisa_email"> E-mail</label>
            </div>

            <div>
                <label class="block font-medium">Data de Abertura da Vaga</label>
                <input type="date" name="data_abertura" required class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-medium">Previsão de Entrada</label>
                <input type="date" name="previsao_entrada" required class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">💾 Salvar</button>
        </form>

        <div class="mt-4">
            <a href="?page=home_vaga" class="text-blue-600 hover:underline">← Voltar</a>
        </div>
    </div>
</body>
</html>
