<?php
$authFlash = isset($authFlash) && is_array($authFlash) ? $authFlash : null;
$authFlashType = $authFlash && isset($authFlash['type']) && $authFlash['type'] === 'success'
    ? 'success'
    : 'error';
$canManageStudents = !empty($canManageStudents);
$canManageUsers = !empty($canManageUsers);
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 5</p>
    <h1>Dashboard</h1>
    <p>Acesse os módulos liberados para o perfil da sua conta.</p>
</section>

<?php if ($authFlash && !empty($authFlash['message'])): ?>
    <div
        class="alert alert-<?= e($authFlashType) ?>"
        role="<?= $authFlashType === 'error' ? 'alert' : 'status' ?>"
    >
        <?= e($authFlash['message']) ?>
    </div>
<?php endif; ?>

<section class="panel" aria-labelledby="modulos-heading">
    <div class="alert alert-info" role="status">
        Bem-vindo, <?= e($usuario['nome'] ?? '') ?>.
        Perfil: <?= e($usuario['perfil_label'] ?? '') ?>.
    </div>

    <div class="section-heading">
        <h2 id="modulos-heading">Módulos permitidos</h2>
        <p class="section-description">A autorização também é verificada no servidor ao acessar cada rota.</p>
    </div>

    <div class="module-grid">
        <a href="<?= e(url('/alunos')) ?>">
            <span>Alunos</span>
            <small><?= $canManageStudents ? 'Cadastro, consulta, edição e exclusão' : 'Consulta dos registros' ?></small>
        </a>
        <a href="<?= e(url('/professores')) ?>">
            <span>Professores</span>
            <small>Estrutura inicial</small>
        </a>
        <a href="<?= e(url('/turmas')) ?>">
            <span>Turmas</span>
            <small>Estrutura inicial</small>
        </a>
        <a href="<?= e(url('/disciplinas')) ?>">
            <span>Disciplinas</span>
            <small>Estrutura inicial</small>
        </a>
        <a href="<?= e(url('/matriculas')) ?>">
            <span>Matrículas</span>
            <small>Estrutura inicial</small>
        </a>
        <?php if ($canManageUsers): ?>
            <a href="<?= e(url('/usuarios')) ?>">
                <span>Usuários</span>
                <small>Contas e perfis de acesso</small>
            </a>
        <?php endif; ?>
    </div>
</section>
