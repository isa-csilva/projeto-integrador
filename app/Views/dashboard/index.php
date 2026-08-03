<section class="page-header compact">
    <p class="eyebrow">Painel do projeto</p>
    <h1>Dashboard</h1>
    <p>Visão geral dos módulos planejados para o Sistema de Gestão Escolar.</p>
</section>

<section class="panel" aria-labelledby="modulos-heading">
    <?php if ($usuario): ?>
        <div class="alert alert-info" role="status">
            Sessão de demonstração ativa para <?= e($usuario['nome']) ?>, perfil <?= e($usuario['perfil']) ?>.
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nenhuma sessão de demonstração está ativa.
            <a href="<?= e(url('/login')) ?>">Acessar o login</a>.
        </div>
    <?php endif; ?>

    <div class="section-heading">
        <h2 id="modulos-heading">Módulos do projeto</h2>
        <p class="section-description">Nesta entrega, somente o cadastro e a listagem de alunos utilizam persistência. As demais páginas apresentam a estrutura inicial de rotas.</p>
    </div>

    <div class="module-grid">
        <a href="<?= e(url('/alunos')) ?>">
            <span>Alunos</span>
            <small>Cadastro e listagem</small>
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
        <a href="<?= e(url('/usuarios')) ?>">
            <span>Usuários</span>
            <small>Estrutura inicial</small>
        </a>
    </div>
</section>
