<?php
$errors = isset($errors) && is_array($errors) ? $errors : array();
$old = isset($old) && is_array($old) ? $old : array();
?>

<section class="page-header compact">
    <p class="eyebrow">Demonstração</p>
    <h1>Login</h1>
    <p>Este formulário valida os campos e inicia uma sessão local de demonstração. A autenticação persistente e o controle de perfis serão implementados em uma etapa futura.</p>
</section>

<section class="panel narrow" aria-labelledby="login-heading">
    <h2 id="login-heading" class="sr-only">Formulário de login</h2>

    <form class="form" action="<?= e(url('/login')) ?>" method="post">
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
                placeholder="admin@escola.com"
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
                placeholder="Digite sua senha"
                required
                <?php if (isset($errors['senha'])): ?>aria-invalid="true" aria-describedby="senha-error"<?php endif; ?>
            >
            <?php if (isset($errors['senha'])): ?>
                <small id="senha-error" class="form-error"><?= e($errors['senha']) ?></small>
            <?php endif; ?>
        </div>

        <button class="button full" type="submit">Iniciar sessão de demonstração</button>
    </form>
</section>
