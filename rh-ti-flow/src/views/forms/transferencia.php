<?php
$edicao = isset($form_dados);
$dados = $edicao ? $form_dados : [];

function campo($nome) {
    global $dados;
    return htmlspecialchars($dados[$nome] ?? '');
}

function marcado($valor, $array) {
    return in_array($valor, $array ?? []) ? 'checked' : '';
}
?>

<form method="post" action="?page=<?= $edicao ? 'salvar_edicao' : 'salvar_solicitacao' ?>">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= campo('id') ?>">
    <?php endif; ?>
    <input type="hidden" name="tipo" value="transferencia">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label>Nome completo:</label>
            <input type="text" name="nome" value="<?= campo('nome') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>CPF:</label>
            <input type="text" name="cpf" value="<?= campo('cpf') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>RG:</label>
            <input type="text" name="rg" value="<?= campo('rg') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Loja anterior:</label>
            <input type="text" name="loja_anterior" value="<?= campo('loja_anterior') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Nova loja:</label>
            <input type="text" name="nova_loja" value="<?= campo('nova_loja') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Data de início na nova loja:</label>
            <input type="date" name="data_inicio" value="<?= campo('data_inicio') ?>" class="w-full p-2 border rounded">
        </div>
    </div>

    <div class="mb-4">
        <label>Necessário movimentação de equipamentos?</label>
        <select name="movimentacao_equipamentos" class="w-full p-2 border rounded">
            <option value="nao" <?= campo('movimentacao_equipamentos') === 'nao' ? 'selected' : '' ?>>Não</option>
            <option value="sim" <?= campo('movimentacao_equipamentos') === 'sim' ? 'selected' : '' ?>>Sim</option>
        </select>
    </div>

    <fieldset class="mb-4">
        <legend class="font-semibold mb-2">Atualização de acessos:</legend>
        <?php $acessos = $dados['acessos'] ?? []; ?>
        <label class="block"><input type="checkbox" name="acessos[]" value="rede" <?= marcado('rede', $acessos) ?> class="mr-2"> Rede</label>
        <label class="block"><input type="checkbox" name="acessos[]" value="vd" <?= marcado('vd', $acessos) ?> class="mr-2"> VD+</label>
        <label class="block"><input type="checkbox" name="acessos[]" value="retaguarda" <?= marcado('retaguarda', $acessos) ?> class="mr-2"> Retaguarda GB</label>
        <label class="block"><input type="checkbox" name="acessos[]" value="mobshop" <?= marcado('mobshop', $acessos) ?> class="mr-2"> Mobshop</label>
        <label class="block"><input type="checkbox" name="acessos[]" value="pdv" <?= marcado('pdv', $acessos) ?> class="mr-2"> PDV</label>
        <label class="block"><input type="checkbox" name="acessos[]" value="extranet" <?= marcado('extranet', $acessos) ?> class="mr-2"> Extranet</label>
    </fieldset>

    <div class="mb-4">
        <label>Observações adicionais:</label>
        <textarea name="observacoes" rows="3" class="w-full p-2 border rounded"><?= campo('observacoes') ?></textarea>
    </div>

</form>
