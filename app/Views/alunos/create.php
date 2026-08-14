<?php
$formAction = url('/alunos/salvar');
$formHeading = 'Formulário de cadastro de aluno';
$submitLabel = 'Cadastrar aluno';
$cancelUrl = url('/alunos');
$formNote = 'O aluno poderá ser editado ou excluído posteriormente pela listagem.';
?>

<section class="page-header compact">
    <p class="eyebrow">Entrega Parcial 4</p>
    <h1>Novo aluno</h1>
    <p>Preencha os campos abaixo para cadastrar um aluno. Todos os campos são obrigatórios.</p>
</section>

<?php require VIEW_PATH . '/alunos/_form.php'; ?>
