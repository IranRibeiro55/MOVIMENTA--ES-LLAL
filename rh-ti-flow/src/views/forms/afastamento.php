<?php
$edicao = isset($form_dados);
$dados = $edicao ? $form_dados : [];
?>

<form method="post" action="?page=<?= $edicao ? 'salvar_edicao' : 'salvar_solicitacao' ?>">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
    <?php endif; ?>
    <input type="hidden" name="tipo" value="afastamento">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label>Nome completo:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>CPF:</label>
            <input type="text" name="cpf" value="<?= htmlspecialchars($dados['cpf'] ?? '') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>RG:</label>
            <input type="text" name="rg" value="<?= htmlspecialchars($dados['rg'] ?? '') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Telefone pessoal:</label>
            <input type="text" name="telefone" value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>E-mail pessoal:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($dados['email'] ?? '') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Tipo:</label>
            <select name="tipo_afastamento" class="w-full p-2 border rounded">
                <option value="">-- Selecione --</option>
                <option value="afastamento" <?= ($dados['tipo_afastamento'] ?? '') === 'afastamento' ? 'selected' : '' ?>>Afastamento</option>
                <option value="retorno" <?= ($dados['tipo_afastamento'] ?? '') === 'retorno' ? 'selected' : '' ?>>Retorno</option>
                <option value="ferias" <?= ($dados['tipo_afastamento'] ?? '') === 'Ferias' ? 'selected' : '' ?>>Férias</option>
            </select>
        </div>
        <div>
            <label>Data de início / retorno:</label>
            <input type="date" name="data_inicio" value="<?= htmlspecialchars($dados['data_inicio'] ?? '') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Previsão de término:</label>
            <input type="date" name="data_termino" value="<?= htmlspecialchars($dados['data_termino'] ?? '') ?>" class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Manter acessos?</label>
            <select name="manter_acessos" class="w-full p-2 border rounded">
                <option value="nao" <?= ($dados['manter_acessos'] ?? '') === 'nao' ? 'selected' : '' ?>>Não</option>
                <option value="sim" <?= ($dados['manter_acessos'] ?? '') === 'sim' ? 'selected' : '' ?>>Sim</option>
            </select>
        </div>
        <div>
            <label>Cargo:</label>
            <input type="text" name="cargo" value="<?= htmlspecialchars($dados['cargo'] ?? '') ?>" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label>Loja / Unidade:</label>
            <input type="text" name="loja" value="<?= htmlspecialchars($dados['loja'] ?? '') ?>" class="w-full p-2 border rounded" required>
        </div>
    </div>

    <fieldset class="mb-4">
        <legend class="font-semibold mb-2">Ações necessárias:</legend>
        <?php $acoes = $dados['acoes'] ?? []; ?>
        <label class="block"><input type="checkbox" name="acoes[]" value="bloqueio_email" <?= in_array('bloqueio_email', $acoes) ? 'checked' : '' ?> class="mr-2"> Bloqueio de e-mail</label>
        <label class="block"><input type="checkbox" name="acoes[]" value="revogacao_acessos" <?= in_array('revogacao_acessos', $acoes) ? 'checked' : '' ?> class="mr-2"> Revogação de acessos</label>
        <label class="block"><input type="checkbox" name="acoes[]" value="coleta_equip" <?= in_array('coleta_equip', $acoes) ? 'checked' : '' ?> class="mr-2"> Coleta de equipamentos</label>
        <label class="block"><input type="checkbox" name="acoes[]" value="desativar_sistemas" <?= in_array('desativar_sistemas', $acoes) ? 'checked' : '' ?> class="mr-2"> Desativação de sistemas</label>
    </fieldset>

    <div class="mb-4">
        <label>Observações adicionais:</label>
        <textarea name="observacoes" rows="3" class="w-full p-2 border rounded"><?= htmlspecialchars($dados['observacoes'] ?? '') ?></textarea>
    </div>

 
</form>