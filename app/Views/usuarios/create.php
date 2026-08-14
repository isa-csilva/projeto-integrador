<?php
$errors = isset($errors) && is_array($errors) ? $errors : array();
$old = isset($old) && is_array($old) ? $old : array();
$formError = isset($formError) ? $formError : null;
$perfis = isset($perfis) && is_array($perfis) ? $perfis : array();
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 5</p>
    <h1>Novo usuário</h1>
    <p>Cadastre uma conta e escolha somente as permissões necessárias para sua função.</p>
</section>

<section class="panel" aria-labelledby="usuario-form-heading">
    <h2 id="usuario-form-heading" class="sr-only">Formulário de cadastro de usuário</h2>

    <?php if (!empty($formError)): ?>
        <div class="alert alert-error" role="alert" tabindex="-1"><?= e($formError) ?></div>
    <?php endif; ?>

    <form class="form grid" action="<?= e(url('/usuarios/salvar')) ?>" method="post">
        <input type="hidden" name="_token" value="<?= e(csrfToken()) ?>">

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
                <?php if (isset($errors['nome'])): ?>aria-invalid="true" aria-describedby="usuario-nome-error"<?php endif; ?>
            >
            <?php if (isset($errors['nome'])): ?>
                <small id="usuario-nome-error" class="form-error"><?= e($errors['nome']) ?></small>
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
                <?php if (isset($errors['email'])): ?>aria-invalid="true" aria-describedby="usuario-email-error"<?php endif; ?>
            >
            <?php if (isset($errors['email'])): ?>
                <small id="usuario-email-error" class="form-error"><?= e($errors['email']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="senha">Senha inicial</label>
            <input
                id="senha"
                name="senha"
                type="password"
                autocomplete="new-password"
                maxlength="72"
                required
                <?php if (isset($errors['senha'])): ?>aria-invalid="true" aria-describedby="usuario-senha-help usuario-senha-error"<?php else: ?>aria-describedby="usuario-senha-help"<?php endif; ?>
            >
            <small id="usuario-senha-help">Use pelo menos 8 caracteres.</small>
            <?php if (isset($errors['senha'])): ?>
                <small id="usuario-senha-error" class="form-error"><?= e($errors['senha']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="perfil">Perfil de acesso</label>
            <select
                id="perfil"
                name="perfil"
                required
                <?php if (isset($errors['perfil'])): ?>aria-invalid="true" aria-describedby="usuario-perfil-error"<?php endif; ?>
            >
                <?php foreach ($perfis as $value => $label): ?>
                    <option
                        value="<?= e($value) ?>"
                        <?php if (($old['perfil'] ?? '') === $value): ?>selected<?php endif; ?>
                    ><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['perfil'])): ?>
                <small id="usuario-perfil-error" class="form-error"><?= e($errors['perfil']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-actions full-row">
            <a class="button secondary" href="<?= e(url('/usuarios')) ?>">Cancelar</a>
            <button class="button" type="submit">Cadastrar usuário</button>
        </div>
    </form>

    <p class="feature-note">Administrador: acesso total. Secretaria: gerencia alunos. Consulta: somente leitura dos módulos acadêmicos.</p>
</section>
