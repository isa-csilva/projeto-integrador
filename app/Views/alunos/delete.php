<?php
$alunoId = isset($aluno['id']) ? (int) $aluno['id'] : 0;
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 4</p>
    <h1>Excluir aluno</h1>
    <p>Confira o registro antes de confirmar a exclusão.</p>
</section>

<section class="panel narrow" aria-labelledby="delete-heading">
    <h2 id="delete-heading">Confirmar exclusão</h2>
    <p class="alert alert-error">
        Esta ação é permanente e não poderá ser desfeita.
    </p>

    <dl class="record-summary">
        <div>
            <dt>Nome</dt>
            <dd><?= e($aluno['nome'] ?? '') ?></dd>
        </div>
        <div>
            <dt>E-mail</dt>
            <dd><?= e($aluno['email'] ?? '') ?></dd>
        </div>
        <div>
            <dt>Matrícula</dt>
            <dd><?= e($aluno['matricula'] ?? '') ?></dd>
        </div>
        <div>
            <dt>Turma</dt>
            <dd><?= e($aluno['turma'] ?? '') ?></dd>
        </div>
    </dl>

    <form class="form" action="<?= e(url('/alunos/' . $alunoId . '/excluir')) ?>" method="post">
        <input type="hidden" name="_token" value="<?= e(csrfToken()) ?>">

        <div class="form-actions">
            <a class="button secondary" href="<?= e(url('/alunos')) ?>">Cancelar</a>
            <button class="button danger" type="submit">Excluir definitivamente</button>
        </div>
    </form>
</section>
