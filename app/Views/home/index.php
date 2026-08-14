<?php
$authenticated = !empty($authenticated);
$canManageStudents = !empty($canManageStudents);
$canManageUsers = !empty($canManageUsers);
?>

<section class="page-header">
    <p class="eyebrow">Entregas Parciais 2, 3, 4 e 5</p>
    <h1>Sistema de Gestão Escolar</h1>
    <p>Aplicação acadêmica em PHP com MVC, CRUD de alunos, autenticação persistente e autorização por perfil.</p>
</section>

<section class="summary-grid" aria-label="Resumo das entregas">
    <?php foreach ($cards as $card): ?>
        <article class="summary-card">
            <span><?= e($card['label']) ?></span>
            <strong><?= e($card['value']) ?></strong>
        </article>
    <?php endforeach; ?>
</section>

<section class="panel" aria-labelledby="rotas-heading">
    <div class="panel-header">
        <div>
            <h2 id="rotas-heading">Rotas disponíveis</h2>
            <p class="section-description">Os links respeitam a sessão, o perfil e a subpasta de instalação.</p>
        </div>
        <a class="button" href="<?= e(url($authenticated ? '/dashboard' : '/login')) ?>">
            <?= $authenticated ? 'Abrir dashboard' : 'Entrar no sistema' ?>
        </a>
    </div>
    <div class="route-list">
        <a href="<?= e(url('/')) ?>"><code>/</code></a>
        <?php if (!$authenticated): ?>
            <a href="<?= e(url('/login')) ?>"><code>/login</code></a>
        <?php else: ?>
            <a href="<?= e(url('/dashboard')) ?>"><code>/dashboard</code></a>
            <a href="<?= e(url('/alunos')) ?>"><code>/alunos</code></a>
            <?php if ($canManageStudents): ?>
                <a href="<?= e(url('/alunos/criar')) ?>"><code>/alunos/criar</code></a>
            <?php endif; ?>
            <a href="<?= e(url('/professores')) ?>"><code>/professores</code></a>
            <a href="<?= e(url('/turmas')) ?>"><code>/turmas</code></a>
            <a href="<?= e(url('/disciplinas')) ?>"><code>/disciplinas</code></a>
            <a href="<?= e(url('/matriculas')) ?>"><code>/matriculas</code></a>
            <?php if ($canManageUsers): ?>
                <a href="<?= e(url('/usuarios')) ?>"><code>/usuarios</code></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
