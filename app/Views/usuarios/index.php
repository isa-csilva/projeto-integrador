<?php
$usuarios = isset($usuarios) && is_array($usuarios) ? $usuarios : array();
$flash = isset($flash) && is_array($flash) ? $flash : null;
$flashType = $flash && isset($flash['type']) && $flash['type'] === 'error' ? 'error' : 'success';
$loadError = isset($loadError) ? $loadError : null;
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 5</p>
    <h1>Usuários e perfis</h1>
    <p>Gerencie as contas que podem acessar o sistema. Somente administradores visualizam este módulo.</p>
</section>

<?php if ($flash && !empty($flash['message'])): ?>
    <div
        class="alert alert-<?= e($flashType) ?>"
        role="<?= $flashType === 'error' ? 'alert' : 'status' ?>"
    >
        <?= e($flash['message']) ?>
    </div>
<?php endif; ?>

<section class="panel" aria-labelledby="usuarios-heading">
    <div class="panel-header">
        <div>
            <h2 id="usuarios-heading">Contas cadastradas</h2>
            <p class="section-description">Os hashes de senha nunca são exibidos nem enviados para a interface.</p>
        </div>
        <a class="button" href="<?= e(url('/usuarios/criar')) ?>">Novo usuário</a>
    </div>

    <?php if (!empty($loadError)): ?>
        <div class="alert alert-error" role="alert"><?= e($loadError) ?></div>
    <?php else: ?>
        <div class="table-wrap" tabindex="0" aria-label="Tabela de usuários cadastrados">
            <table>
                <caption class="sr-only">Lista de usuários e seus perfis</caption>
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Perfil</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td class="empty-state" colspan="4">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td data-label="Nome"><?= e($usuario['nome'] ?? '') ?></td>
                                <td data-label="E-mail"><?= e($usuario['email'] ?? '') ?></td>
                                <td data-label="Perfil"><?= e($usuario['perfil_label'] ?? '') ?></td>
                                <td data-label="Status"><?= e($usuario['status_label'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
