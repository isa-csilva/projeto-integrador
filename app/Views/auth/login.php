<?php
$errors = isset($errors) && is_array($errors) ? $errors : array();
$old = isset($old) && is_array($old) ? $old : array();
$formError = isset($formError) ? $formError : null;
$flash = isset($flash) && is_array($flash) ? $flash : null;
$flashType = $flash && isset($flash['type']) && $flash['type'] === 'success'
    ? 'success'
    : 'error';
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 5</p>
    <h1>Entrar</h1>
    <p>Use uma conta ativa para acessar os módulos permitidos ao seu perfil.</p>
</section>

<section class="panel narrow" aria-labelledby="login-heading">
    <h2 id="login-heading" class="sr-only">Formulário de login</h2>

    <?php if ($flash && !empty($flash['message'])): ?>
        <div
            class="alert alert-<?= e($flashType) ?>"
            role="<?= $flashType === 'error' ? 'alert' : 'status' ?>"
        >
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($formError)): ?>
        <div class="alert alert-error" role="alert" tabindex="-1">
            <?= e($formError) ?>
        </div>
    <?php endif; ?>

    <form class="form" action="<?= e(url('/login')) ?>" method="post">
        <input type="hidden" name="_token" value="<?= e(csrfToken()) ?>">

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
                <?php if (isset($errors['email'])): ?>aria-invalid="true" aria-describedby="login-email-error"<?php endif; ?>
            >
            <?php if (isset($errors['email'])): ?>
                <small id="login-email-error" class="form-error"><?= e($errors['email']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="senha">Senha</label>
            <input
                id="senha"
                name="senha"
                type="password"
                autocomplete="current-password"
                maxlength="255"
                required
                <?php if (isset($errors['senha'])): ?>aria-invalid="true" aria-describedby="senha-error"<?php endif; ?>
            >
            <?php if (isset($errors['senha'])): ?>
                <small id="senha-error" class="form-error"><?= e($errors['senha']) ?></small>
            <?php endif; ?>
        </div>

        <button class="button full" type="submit">Entrar</button>
    </form>

    <p class="feature-note">A senha é comparada com o hash armazenado no banco e nunca é salva na sessão.</p>
</section>
