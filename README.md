# Sistema de Gestão Escolar

Aplicação web acadêmica para organizar informações escolares em uma interface simples e responsiva. O projeto é desenvolvido na disciplina **Projeto e Implementação de Sistemas para Web II** e usa PHP sem framework, arquitetura MVC e MySQL.

Repositório oficial: [github.com/isa-csilva/projeto-integrador](https://github.com/isa-csilva/projeto-integrador)

Branch principal do projeto: `master`

## Objetivo

Centralizar dados acadêmicos e administrativos de pequenas e médias instituições de ensino, reduzindo controles manuais e oferecendo uma base organizada para cadastros, consultas e futuras rotinas escolares.

Nesta etapa, o foco funcional é o cadastro e a listagem de alunos. Administradores, profissionais da secretaria e professores são os principais perfis previstos para a evolução do sistema.

## Situação das entregas parciais

| Entrega | Situação | Escopo concluído |
| --- | --- | --- |
| Parcial 2 — Estrutura MVC e Rotas | Concluída | Estrutura MVC, controllers e views iniciais, front controller, roteamento por método HTTP, páginas 404 e 405 e repositório remoto atualizado. |
| Parcial 3 — CRUD Inicial | Concluída | Conexão MySQL com PDO, schema versionado e operações Create e Read de alunos, com validação, duplicidades, PRG e mensagens de erro seguras. |

### Entrega Parcial 2 — Estrutura MVC e Rotas

- Controllers recebem as requisições e coordenam o fluxo.
- O model concentra o acesso aos dados de alunos.
- As views recebem dados prontos e não executam consultas SQL.
- `public/index.php` é o único front controller da aplicação.
- `core/Router.php` diferencia caminho e método HTTP.
- Rotas inexistentes exibem uma página 404.
- Métodos incompatíveis com uma rota exibem uma página 405.
- Links e redirecionamentos consideram a instalação dentro de uma subpasta do `htdocs`.
- Saídas dinâmicas são escapadas antes de serem exibidas nas views.

### Entrega Parcial 3 — CRUD Inicial

- A conexão PDO é centralizada e reutilizada durante a requisição.
- O banco e a tabela `alunos` são criados por um script SQL idempotente.
- O cadastro persiste alunos no MySQL com prepared statements.
- A listagem consulta os alunos diretamente no MySQL.
- Nome, e-mail, matrícula e turma são validados.
- E-mail e matrícula duplicados são tratados com mensagens próximas aos campos.
- Os valores informados são preservados quando há erro de validação.
- Após um cadastro válido, o fluxo Post/Redirect/Get redireciona para a listagem e exibe uma mensagem flash.
- Falhas internas exibem uma resposta genérica; detalhes técnicos são enviados ao log do PHP.

> Neste projeto, “CRUD inicial” significa **Create e Read**, que são o escopo da Parcial 3. Update e Delete permanecem para a Parcial 4.

## Tecnologias

- PHP 7.4 ou superior, orientado a objetos;
- arquitetura MVC sem framework e sem dependências do Composer;
- MySQL;
- PDO com a extensão `pdo_mysql`;
- Apache e XAMPP para o ambiente local;
- HTML5, CSS3 e JavaScript;
- Git e GitHub.

## Estrutura MVC

```text
projeto-integrador/
├── app/
│   ├── Controllers/
│   │   ├── AlunoController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ErrorController.php
│   │   ├── HomeController.php
│   │   └── ModuloController.php
│   ├── Models/
│   │   └── Aluno.php
│   └── Views/
│       ├── alunos/
│       ├── auth/
│       ├── dashboard/
│       ├── errors/
│       ├── home/
│       ├── layouts/
│       └── modulos/
├── config/
│   └── database.php
├── core/
│   ├── Controller.php
│   ├── Database.php
│   ├── Router.php
│   └── helpers.php
├── database/
│   └── schema.sql
├── public/
│   ├── css/
│   ├── js/
│   ├── .htaccess
│   └── index.php
├── .gitignore
└── README.md
```

O fluxo principal é:

1. O Apache encaminha a requisição para `public/index.php` por meio de `public/.htaccess`.
2. O front controller registra as rotas e solicita o despacho ao `Router`.
3. O controller correspondente valida a entrada e coordena model e view.
4. O model `Aluno` usa a conexão fornecida por `Database` para consultar ou persistir dados.
5. A view renderiza somente os dados recebidos do controller.

## Funcionalidades concluídas

- Página inicial e dashboard.
- Tela e sessão demonstrativas de login e logout.
- Páginas iniciais dos módulos de professores, turmas, disciplinas, matrículas e usuários.
- Cadastro persistente de alunos.
- Listagem persistente de alunos, inclusive com estado vazio amigável.
- Validação de campos obrigatórios e formato de e-mail.
- Detecção de e-mail e matrícula duplicados.
- Mensagens flash após cadastro.
- Tratamento de erros 404, 405 e 500.
- Interface responsiva em português brasileiro.

A autenticação atual é demonstrativa: ela cria uma sessão após validar o preenchimento do formulário, mas ainda não consulta usuários nem senhas no banco.

## Funcionalidades futuras

- Autenticação persistente com senhas armazenadas por hash.
- Perfis de acesso e autorização de rotas.
- Visualização detalhada, edição e exclusão de alunos.
- Upload de foto ou documentos do aluno.
- CRUD de professores, turmas, disciplinas, matrículas e usuários.
- Vínculos entre professores, disciplinas, alunos e turmas.
- Notas, frequência, boletim e relatórios.
- DER/MER, documentação final e publicação em ambiente de produção.

As rotas de editar, atualizar e excluir alunos não são apresentadas nem registradas como funcionalidades concluídas nesta entrega.

## Rotas disponíveis

| Método | Caminho | Finalidade |
| --- | --- | --- |
| `GET` | `/` | Página inicial. |
| `GET` | `/dashboard` | Painel principal. |
| `GET` | `/login` | Formulário de login demonstrativo. |
| `POST` | `/login` | Validação do formulário e criação da sessão demonstrativa. |
| `GET` | `/logout` | Encerramento da sessão e retorno ao login. |
| `GET` | `/alunos` | Listagem dos alunos consultados no MySQL. |
| `GET` | `/alunos/criar` | Formulário de cadastro de aluno. |
| `POST` | `/alunos/salvar` | Validação e persistência de um novo aluno. |
| `GET` | `/professores` | Página inicial do módulo de professores. |
| `GET` | `/turmas` | Página inicial do módulo de turmas. |
| `GET` | `/disciplinas` | Página inicial do módulo de disciplinas. |
| `GET` | `/matriculas` | Página inicial do módulo de matrículas. |
| `GET` | `/usuarios` | Página inicial do módulo de usuários. |

Ao acessar um caminho não registrado, a aplicação responde com 404. Ao usar um método HTTP não aceito por uma rota existente, responde com 405. Erros inesperados são tratados por uma página 500 genérica.

## Requisitos para execução

- XAMPP com Apache, PHP 7.4 ou superior e MySQL/MariaDB;
- extensões PHP `PDO` e `pdo_mysql` habilitadas;
- módulo `mod_rewrite` do Apache habilitado;
- permissão `AllowOverride` para que `public/.htaccess` seja aplicado;
- navegador web atualizado;
- Git, caso o projeto seja obtido por clone.

O projeto não requer Laravel, Symfony, Composer ou instalação de pacotes.

## Instalação com XAMPP

1. Coloque o projeto dentro do diretório `htdocs` do XAMPP. No Windows, o caminho usual é:

   ```text
   C:\xampp\htdocs\projeto-integrador
   ```

   Também é possível clonar diretamente:

   ```powershell
   Set-Location C:\xampp\htdocs
   git clone https://github.com/isa-csilva/projeto-integrador.git
   Set-Location projeto-integrador
   git switch master
   ```

2. Abra o painel do XAMPP e inicie **Apache** e **MySQL**.
3. Importe `database/schema.sql` conforme a seção seguinte.
4. Mantenha os valores padrão do banco ou configure as variáveis de ambiente necessárias.
5. Acesse a URL local da aplicação.

Se as rotas internas retornarem 404 do próprio Apache, confirme se `mod_rewrite` está habilitado e se o Apache permite a leitura do `.htaccess`. Depois de alterar a configuração do Apache, reinicie o serviço.

## Criação e importação do banco

O arquivo `database/schema.sql`:

- cria o banco `sistema_escolar`, caso ele ainda não exista;
- usa `utf8mb4`;
- cria a tabela `alunos`, caso ela ainda não exista;
- não executa `DROP DATABASE` nem `DROP TABLE`.

Por isso, o script pode ser importado novamente sem apagar tabelas ou registros existentes.

### Importação pelo phpMyAdmin

1. Com o MySQL iniciado, acesse [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
2. Abra a aba **Importar**.
3. Selecione o arquivo `database/schema.sql` deste projeto.
4. Mantenha o formato SQL e confirme a importação.
5. Verifique se o banco `sistema_escolar` e a tabela `alunos` foram criados.

### Importação pela linha de comando

No Prompt de Comando do Windows, a partir da raiz do projeto:

```bat
C:\xampp\mysql\bin\mysql.exe -u root < database\schema.sql
```

Se o usuário do MySQL possuir senha, use a opção `-p` e informe-a somente quando o cliente solicitar.

## Configuração do banco de dados

`config/database.php` lê as seguintes variáveis de ambiente e fornece padrões adequados a uma instalação local comum do XAMPP:

| Variável | Padrão local | Finalidade |
| --- | --- | --- |
| `DB_HOST` | `127.0.0.1` | Endereço do servidor MySQL. |
| `DB_PORT` | `3306` | Porta do servidor MySQL. |
| `DB_NAME` | `sistema_escolar` | Nome do banco da aplicação. |
| `DB_USER` | `root` | Usuário local do MySQL. |
| `DB_PASS` | vazio | Senha local do MySQL. |

Representação dos valores padrão:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=sistema_escolar
DB_USER=root
DB_PASS=
```

Os padrões permitem executar o projeto sem armazenar uma senha privada no repositório. Em outro ambiente, defina as variáveis no sistema ou na configuração do Apache e reinicie o servidor. Os arquivos `.env` locais são ignorados pelo Git, mas o projeto não depende de um carregador de `.env`; criar esse arquivo isoladamente não altera o ambiente do PHP.

A conexão utiliza `utf8mb4`, exceções do PDO, retorno associativo e prepared statements reais:

- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`;
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`;
- `PDO::ATTR_EMULATE_PREPARES => false`.

Não versione senhas reais, dumps com dados pessoais, arquivos enviados por usuários ou detalhes de produção.

## URL local de acesso

Com o projeto na pasta indicada, acesse:

[http://localhost/projeto-integrador/public/](http://localhost/projeto-integrador/public/)

Se a pasta dentro de `htdocs` tiver outro nome, substitua `projeto-integrador` pelo nome usado. Por exemplo, uma pasta `escola` será acessada por `http://localhost/escola/public/`.

## Como demonstrar Create e Read

Antes da demonstração, confirme que Apache e MySQL estão ativos e que `database/schema.sql` foi importado.

1. Acesse `/alunos` e confira a listagem ou a mensagem de que ainda não há alunos.
2. Abra `/alunos/criar`.
3. Preencha nome, e-mail, matrícula e turma com valores inéditos.
4. Envie o formulário.
5. Confirme o redirecionamento para `/alunos`, a mensagem de sucesso e o novo registro na tabela.
6. Atualize a página e confirme que o aluno permanece listado, demonstrando a persistência no MySQL.
7. Tente cadastrar outro aluno com o mesmo e-mail e uma matrícula diferente; confira a mensagem de duplicidade do e-mail.
8. Tente novamente com um e-mail diferente e a mesma matrícula; confira a mensagem de duplicidade da matrícula.
9. Envie um e-mail inválido ou deixe um campo obrigatório vazio; confira as mensagens de validação e a preservação dos demais valores preenchidos.

O campo de upload e as ações de editar e excluir não fazem parte desta demonstração, pois pertencem a etapas futuras.

## Segurança e tratamento de erros

- Entradas do formulário são normalizadas e validadas no controller.
- Consultas usam `prepare()` e `execute()`; os dados do formulário não são concatenados ao SQL.
- As views usam escape de saída para reduzir o risco de XSS.
- O model não produz HTML e as views não acessam o banco.
- Exceções de banco não expõem host, usuário, senha, DSN ou mensagens internas na interface.
- Detalhes técnicos podem ser registrados com `error_log()` para diagnóstico local.
- Arquivos locais, logs, uploads e credenciais são excluídos do versionamento pelo `.gitignore`.

## Repositório e controle de versão

- Repositório: [https://github.com/isa-csilva/projeto-integrador](https://github.com/isa-csilva/projeto-integrador)
- Remoto padrão: `origin`
- Branch atual do projeto: `master`

Não é necessário inicializar outro repositório, adicionar novamente o remoto ou renomear a branch para executar o projeto.
