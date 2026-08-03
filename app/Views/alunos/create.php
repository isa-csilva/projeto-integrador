<?php
$errors = isset($errors) && is_array($errors) ? $errors : array();
$old = isset($old) && is_array($old) ? $old : array();
$formError = isset($formError) ? $formError : null;
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 3</p>
    <h1>Novo aluno</h1>
    <p>Preencha os campos abaixo para cadastrar um aluno. Todos os campos são obrigatórios.</p>
</section>

<section class="panel" aria-labelledby="form-heading">
    <h2 id="form-heading" class="sr-only">Formulário de cadastro de aluno</h2>

    <?php if (!empty($formError)): ?>
        <div class="alert alert-error" role="alert" tabindex="-1">
            <?= e($formError) ?>
        </div>
    <?php endif; ?>

    <form class="form grid" action="<?= e(url('/alunos/salvar')) ?>" method="post">
        <div class="form-field">
            <label for="nome">Nome completo</label>
            <input
                id="nome"
                name="nome"
                type="text"
                value="<?= e($old['nome'] ?? '') ?>"
                autocomplete="name"
                maxlength="120"
                required
                <?php if (isset($errors['nome'])): ?>aria-invalid="true" aria-describedby="nome-error"<?php endif; ?>
            >
            <?php if (isset($errors['nome'])): ?>
                <small id="nome-error" class="form-error"><?= e($errors['nome']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="email">E-mail</label>
            <input
                id="email"
                name="email"
                type="email"
                value="<?= e($old['email'] ?? '') ?>"
                autocomplete="email"
                inputmode="email"
                maxlength="150"
                required
                <?php if (isset($errors['email'])): ?>aria-invalid="true" aria-describedby="email-error"<?php endif; ?>
            >
            <?php if (isset($errors['email'])): ?>
                <small id="email-error" class="form-error"><?= e($errors['email']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="matricula">Matrícula</label>
            <input
                id="matricula"
                name="matricula"
                type="text"
                value="<?= e($old['matricula'] ?? '') ?>"
                autocomplete="off"
                maxlength="30"
                required
                <?php if (isset($errors['matricula'])): ?>aria-invalid="true" aria-describedby="matricula-error"<?php endif; ?>
            >
            <?php if (isset($errors['matricula'])): ?>
                <small id="matricula-error" class="form-error"><?= e($errors['matricula']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="turma">Turma</label>
            <input
                id="turma"
                name="turma"
                type="text"
                value="<?= e($old['turma'] ?? '') ?>"
                autocomplete="off"
                maxlength="50"
                required
                <?php if (isset($errors['turma'])): ?>aria-invalid="true" aria-describedby="turma-error"<?php endif; ?>
            >
            <?php if (isset($errors['turma'])): ?>
                <small id="turma-error" class="form-error"><?= e($errors['turma']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-actions full-row">
            <a class="button secondary" href="<?= e(url('/alunos')) ?>">Cancelar</a>
            <button class="button" type="submit">Cadastrar aluno</button>
        </div>
    </form>

    <p class="feature-note">Edição e exclusão serão implementadas na Entrega Parcial 4; o envio de arquivos ficará para uma etapa futura.</p>
</section>
