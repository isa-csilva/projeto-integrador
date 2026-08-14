<?php
$navigation = array(
    array('path' => '/', 'label' => 'Início')
);

if ($layoutAuthUser !== null) {
    $navigation[] = array('path' => '/dashboard', 'label' => 'Dashboard');
    $navigation[] = array('path' => '/alunos', 'label' => 'Alunos');
    $navigation[] = array('path' => '/professores', 'label' => 'Professores');
    $navigation[] = array('path' => '/turmas', 'label' => 'Turmas');
    $navigation[] = array('path' => '/disciplinas', 'label' => 'Disciplinas');
    $navigation[] = array('path' => '/matriculas', 'label' => 'Matrículas');

    if ($layoutCanManageUsers) {
        $navigation[] = array('path' => '/usuarios', 'label' => 'Usuários');
    }
} else {
    $navigation[] = array('path' => '/login', 'label' => 'Entrar');
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if (!empty($title)): ?><?= e($title) ?> | <?php endif; ?>Sistema Escolar</title>
    <link rel="stylesheet" href="<?= e(url('/css/style.css')) ?>">
</head>
<body>
    <a class="skip-link" href="#conteudo-principal">Ir para o conteúdo principal</a>

    <header class="topbar">
        <a class="brand" href="<?= e(url('/')) ?>">Sistema Escolar</a>

        <div class="topbar-actions">
            <nav class="nav" aria-label="Menu principal">
                <?php foreach ($navigation as $item): ?>
                    <?php $active = isActive($item['path']) === 'active'; ?>
                    <a
                        class="<?= $active ? 'active' : '' ?>"
                        href="<?= e(url($item['path'])) ?>"
                        <?php if ($active): ?>aria-current="page"<?php endif; ?>
                    ><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            </nav>

            <?php if ($layoutAuthUser !== null): ?>
                <div class="session-controls" aria-label="Sessão atual">
                    <div class="session-user">
                        <strong><?= e($layoutAuthUser['nome']) ?></strong>
                        <span><?= e($layoutAuthProfileLabel) ?></span>
                    </div>

                    <form class="logout-form" action="<?= e(url('/logout')) ?>" method="post">
                        <input type="hidden" name="_token" value="<?= e(csrfToken()) ?>">
                        <button class="nav-button" type="submit">Sair</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main id="conteudo-principal" class="page" tabindex="-1">
        <?php require $viewFile; ?>
    </main>

    <script src="<?= e(url('/js/app.js')) ?>"></script>
</body>
</html>
