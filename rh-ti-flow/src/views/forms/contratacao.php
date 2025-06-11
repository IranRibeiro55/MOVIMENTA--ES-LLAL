<?php
$edicao = isset($form_dados);
$dados = $edicao ? $form_dados : [];
?>

<form method="post" action="?page=<?= $edicao ? 'salvar_edicao' : 'salvar_solicitacao' ?>">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
    <?php endif; ?>
    <input type="hidden" name="tipo" value="contratacao">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <?php
        function campo($nome) {
            global $dados;
            return htmlspecialchars($dados[$nome] ?? '');
        }
        ?>
        <div><label>Nome completo:</label><input type="text" name="nome" value="<?= campo('nome') ?>" required class="w-full p-2 border rounded"></div>
        <div><label>CPF:</label><input type="text" name="cpf" value="<?= campo('cpf') ?>" required class="w-full p-2 border rounded"></div>
        <div><label>RG:</label><input type="text" name="rg" value="<?= campo('rg') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Telefone pessoal:</label><input type="text" name="telefone" value="<?= campo('telefone') ?>" class="w-full p-2 border rounded"></div>
        <div><label>E-mail pessoal:</label><input type="email" name="email" value="<?= campo('email') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Data de admissão:</label><input type="date" name="data_admissao" value="<?= campo('data_admissao') ?>" required class="w-full p-2 border rounded"></div>
        <div><label>Cargo:</label><input type="text" name="cargo" value="<?= campo('cargo') ?>" class="w-full p-2 border rounded"></div>
        <div><label>Loja / Unidade:</label><input type="text" name="loja" value="<?= campo('loja') ?>" class="w-full p-2 border rounded"></div>
    </div>

    <fieldset class="mb-4">
        <legend class="font-semibold mb-2">Equipamentos e acessos necessários:</legend>
        <?php $equip = $dados['equipamentos'] ?? []; ?>
        <?php
        function marcado($valor, $array) {
            return in_array($valor, $array) ? 'checked' : '';
        }
        ?>
        <label class="block"><input type="checkbox" name="equipamentos[]" value="notebook" <?= marcado('notebook', $equip) ?> class="mr-2"> Notebook</label>
        <label class="block"><input type="checkbox" name="equipamentos[]" value="celular" <?= marcado('celular', $equip) ?> class="mr-2"> Celular corporativo</label>
        <label class="block"><input type="checkbox" name="equipamentos[]" value="email_corp" <?= marcado('email_corp', $equip) ?> class="mr-2"> E-mail corporativo</label>
        <label class="block"><input type="checkbox" name="equipamentos[]" value="rede" <?= marcado('rede', $equip) ?> class="mr-2"> Acesso à rede corporativa</label>
        <label class="block"><input type="checkbox" name="equipamentos[]" value="vd_gera" <?= marcado('vd_gera', $equip) ?> class="mr-2"> VD+ (GERA)</label>
    </fieldset>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label>Extranet?</label>
            <select name="extranet" class="w-full p-2 border rounded" onchange="toggleUsuarioExtranet(this.value)">
                <option value="nao" <?= (campo('extranet') === 'nao') ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= (campo('extranet') === 'sim') ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
        <div>
            <label>Usuário Extranet:</label>
            <input type="text" name="usuario_extranet" value="<?= campo('usuario_extranet') ?>" class="w-full p-2 border rounded" id="usuario_extranet">
        </div>

        <div>
            <label>Retaguarda GB?</label>
            <select name="retaguarda" class="w-full p-2 border rounded" onchange="toggleUsuarioGB(this.value)">
                <option value="nao" <?= (campo('retaguarda') === 'nao') ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= (campo('retaguarda') === 'sim') ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
        <div>
            <label>Usuário Varejo Fácil:</label>
            <input type="text" name="usuario_gb" value="<?= campo('usuario_gb') ?>" class="w-full p-2 border rounded" id="usuario_gb">
        </div>

        <div>
            <label>Terá acesso ao PDV?</label>
            <select name="pdv" class="w-full p-2 border rounded">
                <option value="nao" <?= (campo('pdv') === 'nao') ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= (campo('pdv') === 'sim') ? 'selected' : '' ?>>Sim</option>
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
        const extranetSelect = document.querySelector('[name="extranet"]');
        const retaguardaSelect = document.querySelector('[name="retaguarda"]');

        toggleUsuarioExtranet(extranetSelect.value);
        toggleUsuarioGB(retaguardaSelect.value);
    });
</script>
