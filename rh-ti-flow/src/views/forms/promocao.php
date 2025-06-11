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
    <input type="hidden" name="tipo" value="promocao">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div><label>Nome completo:</label><input type="text" name="nome" value="<?= campo('nome') ?>" required class="w-full p-2 border rounded"></div>
        <div><label>CPF:</label><input type="text" name="cpf" value="<?= campo('cpf') ?>" required class="w-full p-2 border rounded"></div>
        <div><label>RG:</label><input type="text" name="rg" value="<?= campo('rg') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Cargo anterior:</label><input type="text" name="cargo_anterior" value="<?= campo('cargo_anterior') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Novo cargo:</label><input type="text" name="novo_cargo" value="<?= campo('novo_cargo') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Data da alteração:</label><input type="date" name="data_alteracao" value="<?= campo('data_alteracao') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Loja anterior:</label><input type="text" name="loja_anterior" value="<?= campo('loja_anterior') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Nova loja:</label><input type="text" name="nova_loja" value="<?= campo('nova_loja') ?>" class="w-full p-2 border rounded"></div>
        <div>
            <label>Manter acessos anteriores?</label>
            <select name="manter_acessos" class="w-full p-2 border rounded">
                <option value="nao" <?= campo('manter_acessos') === 'nao' ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= campo('manter_acessos') === 'sim' ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
    </div>

    <fieldset class="mb-4">
        <legend class="font-semibold mb-2">Necessário adicionar:</legend>
        <?php $adicionais = $dados['adicionais'] ?? []; ?>
        <label class="block"><input type="checkbox" name="adicionais[]" value="notebook" <?= marcado('notebook', $adicionais) ?> class="mr-2"> Notebook</label>
        <label class="block"><input type="checkbox" name="adicionais[]" value="celular" <?= marcado('celular', $adicionais) ?> class="mr-2"> Celular corporativo</label>
        <label class="block"><input type="checkbox" name="adicionais[]" value="email_corp" <?= marcado('email_corp', $adicionais) ?> class="mr-2"> E-mail corporativo</label>
        <label class="block"><input type="checkbox" name="adicionais[]" value="rede" <?= marcado('rede', $adicionais) ?> class="mr-2"> Acesso à rede corporativa</label>
        <label class="block"><input type="checkbox" name="adicionais[]" value="vd_gera" <?= marcado('vd_gera', $adicionais) ?> class="mr-2"> VD+ (GERA)</label>
    </fieldset>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label>Extranet?</label>
            <select name="extranet" class="w-full p-2 border rounded" onchange="toggleUsuarioExtranet(this.value)">
                <option value="nao" <?= campo('extranet') === 'nao' ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= campo('extranet') === 'sim' ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
        <div>
            <label>Usuário Extranet:</label>
            <input type="text" name="usuario_extranet" value="<?= campo('usuario_extranet') ?>" class="w-full p-2 border rounded" id="usuario_extranet">
        </div>

        <div>
            <label>Retaguarda GB (Varejo Fácil)?</label>
            <select name="retaguarda" class="w-full p-2 border rounded" onchange="toggleUsuarioGB(this.value)">
                <option value="nao" <?= campo('retaguarda') === 'nao' ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= campo('retaguarda') === 'sim' ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
        <div>
            <label>Usuário Varejo Fácil:</label>
            <input type="text" name="usuario_gb" value="<?= campo('usuario_gb') ?>" class="w-full p-2 border rounded" id="usuario_gb">
        </div>

        <div>
            <label>Terá acesso ao PDV?</label>
            <select name="pdv" class="w-full p-2 border rounded">
                <option value="nao" <?= campo('pdv') === 'nao' ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= campo('pdv') === 'sim' ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
    </div>

    <div class="mb-4">
        <label>Outros:</label>
        <input type="text" name="outros" value="<?= campo('outros') ?>" class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label>Observações adicionais:</label>
        <textarea name="observacoes" rows="3" class="w-full p-2 border rounded"><?= campo('observacoes') ?></textarea>
    </div>

</form>

<script>
    function toggleUsuarioExtranet(valor) {
        document.getElementById('usuario_extranet').disabled = (valor === 'nao');
    }
    function toggleUsuarioGB(valor) {
        document.getElementById('usuario_gb').disabled = (valor === 'nao');
    }
    document.addEventListener('DOMContentLoaded', () => {
        toggleUsuarioExtranet(document.querySelector('[name="extranet"]').value);
        toggleUsuarioGB(document.querySelector('[name="retaguarda"]').value);
    });
</script>
