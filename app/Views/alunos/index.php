<?php
$flash = isset($flash) && is_array($flash) ? $flash : null;
$flashType = $flash && isset($flash['type']) && $flash['type'] === 'error' ? 'error' : 'success';
$loadError = isset($loadError) ? $loadError : null;
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 3</p>
    <h1>Alunos</h1>
    <p>Consulte os alunos cadastrados no banco de dados ou faça um novo cadastro.</p>
</section>

<?php if ($flash && !empty($flash['message'])): ?>
    <div
        class="alert alert-<?= e($flashType) ?>"
        role="<?= $flashType === 'error' ? 'alert' : 'status' ?>"
        aria-live="<?= $flashType === 'error' ? 'assertive' : 'polite' ?>"
    >
        <?= e($flash['message']) ?>
    </div>
<?php endif; ?>

<section class="panel" aria-labelledby="alunos-heading">
    <div class="panel-header">
        <div>
            <h2 id="alunos-heading">Alunos cadastrados</h2>
            <p class="section-description">A edição e a exclusão serão implementadas na Entrega Parcial 4.</p>
        </div>
        <a class="button" href="<?= e(url('/alunos/criar')) ?>">Novo aluno</a>
    </div>

    <?php if (!empty($loadError)): ?>
        <div class="alert alert-error" role="alert">
            <?= e($loadError) ?>
        </div>
    <?php else: ?>
        <div class="table-wrap" tabindex="0" aria-label="Tabela de alunos cadastrados">
            <table>
                <caption class="sr-only">Lista de alunos cadastrados</caption>
                <thead>
                    <tr>
                        <th scope="col">Matrícula</th>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Turma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alunos)): ?>
                        <tr>
                            <td class="empty-state" colspan="4">
                                Nenhum aluno cadastrado até o momento.
                                <a href="<?= e(url('/alunos/criar')) ?>">Cadastre o primeiro aluno</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alunos as $aluno): ?>
                            <tr>
                                <td data-label="Matrícula"><?= e($aluno['matricula'] ?? '') ?></td>
                                <td data-label="Nome"><?= e($aluno['nome'] ?? '') ?></td>
                                <td data-label="E-mail"><?= e($aluno['email'] ?? '') ?></td>
                                <td data-label="Turma"><?= e($aluno['turma'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
