# Como contribuir

Obrigado pelo interesse em colaborar com o Sistema de Gestão Escolar. Este é
um projeto acadêmico da disciplina Projeto e Implementação de Sistemas para
Web II; toda contribuição deve preservar a arquitetura estudada e ser
compreensível para os integrantes do grupo.

Ao participar, siga o [Código de Conduta](CODE_OF_CONDUCT.md).

## Antes de começar

1. Leia o [README](README.md), especialmente o escopo da entrega atual e as
   funcionalidades futuras.
2. Verifique se já existe uma issue ou pull request para a mesma mudança.
3. Para alterações grandes, combine previamente o escopo com os mantenedores.
4. Nunca publique credenciais, dados pessoais, dumps reais, logs ou uploads de
   usuários.

## Ambiente de desenvolvimento

O projeto requer PHP 7.4 ou superior, Apache, MySQL, PDO com `pdo_mysql` e
`mod_rewrite`. O XAMPP pode fornecer esse ambiente no Windows. A instalação do
banco e a URL local estão documentadas no [README](README.md#7%EF%B8%8F%E2%83%A3-como-executar-com-xampp).

Não adicione frameworks ou dependências sem discussão prévia. O objetivo é
manter a implementação MVC sem framework e compatível com o material da
disciplina.

## Fluxo de trabalho

1. Crie um fork do repositório, se ainda não tiver acesso de escrita.
2. Mantenha o repositório oficial como remoto `upstream`.
3. Atualize a branch `master` antes de iniciar uma mudança.
4. Crie uma branch curta e descritiva.
5. Faça commits pequenos, revise o diff e envie um pull request para
   `isa-csilva/projeto-integrador:master`.

Exemplo:

```powershell
git switch master
git pull upstream master
git switch -c feat/nome-da-funcionalidade
```

Prefixos sugeridos para branches e commits:

- `feat/` ou `feat:` para funcionalidades;
- `fix/` ou `fix:` para correções;
- `docs/` ou `docs:` para documentação;
- `refactor/` ou `refactor:` para reorganizações sem mudança funcional;
- `test/` ou `test:` para testes.

Use `Co-authored-by` somente quando houver colaboração real e com o e-mail que
a pessoa mantém vinculado à conta do GitHub.

## Regras de implementação

- `public/index.php` deve permanecer como único Front Controller.
- As rotas devem ficar centralizadas em `routes/web.php`, sem duplicidades.
- Controllers recebem requisições e coordenam o fluxo; não devem conter SQL ou
  gerar HTML.
- Models concentram consultas e persistência por meio de
  `Database::connect()`.
- Views cuidam da interface, não consultam o banco e escapam valores dinâmicos
  com `e()`.
- Dados externos devem ser validados no servidor antes da persistência.
- Consultas com valores externos devem utilizar prepared statements.
- Mensagens para usuários não podem revelar credenciais, consultas, caminhos
  internos ou detalhes de exceções.
- Alterações no schema devem ser não destrutivas, repetíveis e documentadas.
- Funcionalidades futuras não devem ser apresentadas como concluídas.
- Preserve compatibilidade com PHP 7.4 e com instalação em subpasta do
  `htdocs`.

## Verificações mínimas

Execute na raiz do repositório:

```powershell
$phpFiles = rg --files -g "*.php"
$phpFiles | ForEach-Object { php -l $_ }
git diff --check
```

Se o Node.js estiver disponível, valide também o JavaScript:

```powershell
node --check public/js/app.js
```

Mudanças que envolvam banco de dados devem ser verificadas com MySQL ativo:

- importar `database/schema.sql` em um banco de teste;
- testar os caminhos de sucesso e de validação;
- confirmar a persistência após atualizar a página;
- testar e-mail e matrícula duplicados;
- garantir que nenhum detalhe interno apareça na interface.

## Pull requests

O pull request deve informar:

- objetivo e escopo da mudança;
- arquivos ou módulos principais alterados;
- aulas ou requisitos acadêmicos relacionados;
- testes executados e resultados reais;
- testes pendentes e motivo;
- capturas de tela quando houver mudança visual;
- limitações conhecidas ou próximos passos.

Antes de enviar, confirme:

- [ ] a branch contém apenas alterações relacionadas;
- [ ] o MVC e as responsabilidades das camadas foram preservados;
- [ ] não existem segredos ou dados pessoais no diff;
- [ ] a sintaxe dos arquivos alterados foi verificada;
- [ ] o README foi atualizado quando o comportamento mudou;
- [ ] autoria e fontes externas foram atribuídas corretamente.

## Licença e integridade acadêmica

Ao enviar uma contribuição, você concorda em disponibilizá-la sob os termos da
[Licença MIT](LICENSE). Marcas, avatares, materiais da disciplina e demais
conteúdos de terceiros continuam sujeitos aos direitos de seus respectivos
titulares.

Contribua apenas com trabalho que você possa explicar e cuja autoria possa ser
confirmada. Cite fontes, bibliotecas e trechos adaptados. A aprovação de um
pull request não substitui as regras acadêmicas da disciplina ou da
instituição.
