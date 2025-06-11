<?php
$edicao = isset($form_dados);
$dados = $edicao ? $form_dados : [];
?>

<form method="post" action="?page=<?= $edicao ? 'salvar_edicao' : 'salvar_solicitacao' ?>">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
    <?php endif; ?>
    <input type="hidden" name="tipo" value="desligamento">

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
            <label>Data de desligamento:</label>
            <input type="date" name="data_desligamento" value="<?= htmlspecialchars($dados['data_desligamento'] ?? '') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Cargo:</label>
            <input type="text" name="cargo" value="<?= htmlspecialchars($dados['cargo'] ?? '') ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label>Loja / Unidade:</label>
            <input type="text" name="loja" value="<?= htmlspecialchars($dados['loja'] ?? '') ?>" required class="w-full p-2 border rounded">
        </div>
    </div>

    <fieldset class="mb-4">
        <legend class="font-semibold mb-2">Motivo:</legend>
        <?php $motivo = $dados['motivo'] ?? ''; ?>
        <label class="block"><input type="radio" name="motivo" value="pedido" <?= $motivo === 'pedido' ? 'checked' : '' ?> class="mr-2"> Pedido</label>
        <label class="block"><input type="radio" name="motivo" value="justa_causa" <?= $motivo === 'justa_causa' ? 'checked' : '' ?> class="mr-2"> Justa Causa</label>
        <label class="block"><input type="radio" name="motivo" value="encerramento_contrato" <?= $motivo === 'encerramento_contrato' ? 'checked' : '' ?> class="mr-2"> Encerramento de contrato</label>
    </fieldset>

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
