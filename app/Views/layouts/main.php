<?php
$navigation = array(
    array('path' => '/', 'label' => 'Início'),
    array('path' => '/dashboard', 'label' => 'Dashboard'),
    array('path' => '/alunos', 'label' => 'Alunos'),
    array('path' => '/professores', 'label' => 'Professores'),
    array('path' => '/turmas', 'label' => 'Turmas'),
    array('path' => '/disciplinas', 'label' => 'Disciplinas'),
    array('path' => '/matriculas', 'label' => 'Matrículas'),
    array('path' => '/login', 'label' => 'Login')
);
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
    </header>

    <main id="conteudo-principal" class="page" tabindex="-1">
        <?php require $viewFile; ?>
    </main>

    <script src="<?= e(url('/js/app.js')) ?>"></script>
</body>
</html>
