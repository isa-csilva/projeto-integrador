<?php
$alunoId = isset($aluno['id']) ? (int) $aluno['id'] : 0;
$formAction = url('/alunos/' . $alunoId . '/atualizar');
$formHeading = 'Formulário de edição de aluno';
$submitLabel = 'Salvar alterações';
$cancelUrl = url('/alunos');
$formNote = 'E-mail e matrícula devem permanecer únicos entre os alunos.';
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 4</p>
    <h1>Editar aluno</h1>
    <p>Atualize os dados de <?= e($aluno['nome'] ?? 'aluno') ?> e salve as alterações.</p>
</section>

<?php require VIEW_PATH . '/alunos/_form.php'; ?>
