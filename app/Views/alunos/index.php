<?php
$flash = isset($flash) && is_array($flash) ? $flash : null;
$flashType = $flash && isset($flash['type']) && $flash['type'] === 'error' ? 'error' : 'success';
$loadError = isset($loadError) ? $loadError : null;
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 4</p>
    <h1>Alunos</h1>
    <p>Cadastre, consulte, edite ou exclua os alunos persistidos no banco de dados.</p>
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
            <p class="section-description">Use as ações de cada registro para manter os dados atualizados.</p>
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
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alunos)): ?>
                        <tr>
                            <td class="empty-state" colspan="5">
                                Nenhum aluno cadastrado até o momento.
                                <a href="<?= e(url('/alunos/criar')) ?>">Cadastre o primeiro aluno</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alunos as $aluno): ?>
                            <?php $alunoId = isset($aluno['id']) ? (int) $aluno['id'] : 0; ?>
                            <tr>
                                <td data-label="Matrícula"><?= e($aluno['matricula'] ?? '') ?></td>
                                <td data-label="Nome"><?= e($aluno['nome'] ?? '') ?></td>
                                <td data-label="E-mail"><?= e($aluno['email'] ?? '') ?></td>
                                <td data-label="Turma"><?= e($aluno['turma'] ?? '') ?></td>
                                <td data-label="Ações">
                                    <div class="table-actions">
                                        <a
                                            class="button secondary"
                                            href="<?= e(url('/alunos/' . $alunoId . '/editar')) ?>"
                                            aria-label="Editar aluno <?= e($aluno['nome'] ?? '') ?>"
                                        >Editar</a>
                                        <a
                                            class="button danger"
                                            href="<?= e(url('/alunos/' . $alunoId . '/excluir')) ?>"
                                            aria-label="Excluir aluno <?= e($aluno['nome'] ?? '') ?>"
                                        >Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
