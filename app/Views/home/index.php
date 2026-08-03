<section class="page-header">
    <p class="eyebrow">Entregas Parciais 2 e 3</p>
    <h1>Sistema de Gestão Escolar</h1>
    <p>Aplicação acadêmica em PHP com arquitetura MVC, rotas amigáveis e cadastro de alunos persistido em MySQL.</p>
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
            <p class="section-description">Os links respeitam automaticamente a subpasta de instalação do projeto.</p>
        </div>
        <a class="button" href="<?= e(url('/alunos')) ?>">Abrir alunos</a>
    </div>
    <div class="route-list">
        <a href="<?= e(url('/')) ?>"><code>/</code></a>
        <a href="<?= e(url('/dashboard')) ?>"><code>/dashboard</code></a>
        <a href="<?= e(url('/login')) ?>"><code>/login</code></a>
        <a href="<?= e(url('/alunos')) ?>"><code>/alunos</code></a>
        <a href="<?= e(url('/alunos/criar')) ?>"><code>/alunos/criar</code></a>
        <a href="<?= e(url('/professores')) ?>"><code>/professores</code></a>
        <a href="<?= e(url('/turmas')) ?>"><code>/turmas</code></a>
        <a href="<?= e(url('/disciplinas')) ?>"><code>/disciplinas</code></a>
        <a href="<?= e(url('/matriculas')) ?>"><code>/matriculas</code></a>
        <a href="<?= e(url('/usuarios')) ?>"><code>/usuarios</code></a>
    </div>
</section>
