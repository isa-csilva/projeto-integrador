<img width="100%" src="https://capsule-render.vercel.app/api?type=waving&amp;color=0f766e&amp;height=120&amp;section=header" alt="Cabeçalho decorativo"/>

<h1 align="center">
  🏫
  <br/>
  Sistema de Gestão Escolar
</h1>

<p align="center">
  Aplicação web acadêmica para centralizar cadastros e consultas escolares,
  desenvolvida em PHP com arquitetura MVC e persistência MySQL via PDO.
</p>

<p align="center">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&amp;logoColor=white"/>
  <img alt="MVC" src="https://img.shields.io/badge/arquitetura-MVC-0f766e"/>
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-banco%20de%20dados-4479A1?logo=mysql&amp;logoColor=white"/>
  <img alt="PDO" src="https://img.shields.io/badge/PDO-prepared%20statements-334155"/>
  <img alt="XAMPP" src="https://img.shields.io/badge/XAMPP-ambiente%20local-FB7A24?logo=xampp&amp;logoColor=white"/>
  <img alt="Licença MIT" src="https://img.shields.io/badge/licen%C3%A7a-MIT-22c55e"/>
  <img alt="Parcial 2" src="https://img.shields.io/badge/Parcial%202-conclu%C3%ADda-2ea44f"/>
  <img alt="Parcial 3" src="https://img.shields.io/badge/Parcial%203-implementada-2ea44f"/>
</p>

---

## 👥 Integrantes

<table align="center">
  <tr>
    <td align="center" width="33%">
      <a href="https://github.com/ferreiramateusalencar">
        <img src="https://avatars.githubusercontent.com/u/86336670?v=4" width="110px" alt="Mateus A. Ferreira"/>
      </a>
      <br/>
      <strong>Mateus A. Ferreira</strong>
      <br/>
      <a href="https://github.com/ferreiramateusalencar">@ferreiramateusalencar</a>
    </td>
    <td align="center" width="33%">
      <a href="https://github.com/isa-csilva">
        <img src="https://github.com/isa-csilva.png?size=160" width="110px" alt="Isabelly Costa"/>
      </a>
      <br/>
      <strong>Isabelly Costa</strong>
      <br/>
      <a href="https://github.com/isa-csilva">@isa-csilva</a>
    </td>
    <td align="center" width="33%">
      <a href="https://github.com/barbaracristinavieiradasilva-cpu">
        <img src="https://github.com/barbaracristinavieiradasilva-cpu.png?size=160" width="110px" alt="Bárbara Silva"/>
      </a>
      <br/>
      <strong>Bárbara Silva</strong>
      <br/>
      <a href="https://github.com/barbaracristinavieiradasilva-cpu">@barbaracristinavieiradasilva-cpu</a>
    </td>
  </tr>
</table>

| Campo | Informação |
| --- | --- |
| Projeto | Sistema de Gestão Escolar |
| Disciplina | Projeto e Implementação de Sistemas para Web II |
| Arquitetura | MVC sem framework |
| Entidade principal | Alunos |
| Banco de dados | MySQL |
| Licença | [MIT](LICENSE) |
| Repositório | [isa-csilva/projeto-integrador](https://github.com/isa-csilva/projeto-integrador) |
| Branch principal | <code>master</code> |

---

## 1️⃣ Visão Geral da Solução

O Sistema de Gestão Escolar foi planejado para centralizar informações
acadêmicas e administrativas de pequenas e médias instituições de ensino.
Nesta etapa, a aplicação entrega a base arquitetural completa e o fluxo inicial
de cadastro e consulta de alunos.

A solução implementa:

- front controller e roteamento por método HTTP;
- separação entre controllers, models e views;
- cadastro de alunos persistido no MySQL;
- listagem ordenada dos alunos cadastrados;
- validação dos campos obrigatórios e do formato de e-mail;
- detecção de e-mail e matrícula duplicados;
- mensagens flash e fluxo Post/Redirect/Get;
- páginas de erro 404, 405 e 500;
- interface responsiva em português brasileiro; e
- suporte à instalação em uma subpasta do <code>htdocs</code>.

### Situação das entregas

| Entrega | Situação | Escopo |
| --- | --- | --- |
| Parcial 2 — Estrutura MVC e Rotas | ✅ Concluída | MVC, controllers, views, front controller, rotas e páginas de erro |
| Parcial 3 — CRUD Inicial | ✅ Implementada | PDO, schema MySQL e operações Create e Read de alunos |
| Parcial 4 — CRUD Completo | 🔜 Futura | Visualização, edição e exclusão |

> [!NOTE]
> Nesta etapa, “CRUD inicial” corresponde às operações **Create e Read**.
> Update, Delete e upload de arquivos permanecem explicitamente como
> funcionalidades futuras.

---

## 2️⃣ Arquitetura MVC

O Apache encaminha as requisições não físicas para
<code>public/index.php</code>. O front controller inicializa a aplicação,
carrega as rotas centralizadas em <code>routes/web.php</code> e solicita ao
<code>Router</code> o despacho conforme o caminho e o método HTTP.

### Fluxo principal

~~~text
Navegador
    │ requisição HTTP
    ▼
public/index.php (Front Controller)
    │ carrega as definições
    ▼
routes/web.php
    │ registra as rotas
    ▼
Router
    │ seleciona controller e ação
    ▼
AlunoController
    ├── valida os dados
    ├── coordena mensagens e redirecionamentos
    ├── chama o model Aluno ──► Database ──► MySQL
    └── envia dados prontos para a View
                                │
                                ▼
                         HTML responsivo
~~~

### Responsabilidades

| Camada | Implementação | Responsabilidade |
| --- | --- | --- |
| Entrada | <code>public/index.php</code> | Inicializa a aplicação e despacha a requisição |
| Definição de rotas | <code>routes/web.php</code> | Centraliza caminhos, métodos e handlers sem duplicidades |
| Roteamento | <code>core/Router.php</code> | Diferencia caminhos, parâmetros e métodos HTTP |
| Controller | <code>app/Controllers</code> | Valida entradas e coordena o fluxo |
| Model | <code>app/Models/Aluno.php</code> | Executa as operações de dados com PDO |
| Banco | <code>core/Database.php</code> | Expõe <code>Database::connect()</code> e reutiliza a conexão PDO |
| View | <code>app/Views</code> | Renderiza somente os dados recebidos |
| Layout | <code>app/Views/layouts/main.php</code> | Reutiliza navegação, assets e estrutura HTML |

As views não executam SQL, e o model não gera HTML. Todas as saídas dinâmicas
são escapadas com o helper <code>e()</code>.

### Alinhamento prioritário com as Aulas 02 a 07

| Aula | Conceito aplicado | Evidência no projeto |
| --- | --- | --- |
| Aula 02 — Revisão de Banco de Dados | Banco, tabela, chaves e restrições | <code>database/schema.sql</code> e campos únicos de e-mail/matrícula |
| Aula 03 — Arquitetura Web e MVC | Separação entre entrada, controle, dados e interface | <code>public/</code>, Controllers, Models e Views |
| Aula 04 — Implementação MVC | Front Controller como ponto único de entrada | <code>public/index.php</code> |
| Aula 05 — Rotas e URLs | Rotas centralizadas por caminho e verbo HTTP | <code>routes/web.php</code> e <code>core/Router.php</code> |
| Aula 06 — Models e Banco | Model obtém a conexão reutilizável | <code>Aluno</code> usa <code>Database::connect()</code> |
| Aula 07 — CRUD Create e Read | POST para cadastrar e GET para listar | <code>AlunoController::store()</code> e <code>AlunoController::index()</code> |

### Relação com as demais aulas da disciplina

| Aula | Conceito considerado | Aplicação ou delimitação no projeto |
| --- | --- | --- |
| Aula 01 — POO com PHP | Classes coesas, encapsulamento e reutilização | Controllers herdam de <code>Controller</code>; Router, Database e Model têm responsabilidades próprias |
| Aula 08 — CRUD Update e Delete | Atualização, exclusão segura e confirmação | Operações reservadas para a Parcial 4, sem simular um CRUD completo nesta etapa |
| Aula 09 — Requisições e Respostas HTTP | GET para consulta, POST para envio e validação no servidor | Rotas distinguem os verbos; cadastro usa POST, PRG e mensagens amigáveis |
| Aula 10 — Boas Práticas e Segurança | Responsabilidade única, prepared statements, escape e erros seguros | SQL fica no Model, <code>e()</code> protege a saída e detalhes técnicos vão para o log |
| Aula 11 — Sessões e Cookies | Estado da navegação e encerramento correto da sessão | Sessão inicia antes da saída, armazena mensagens flash e é regenerada no login demonstrativo |
| Aula 12 — Autenticação e Autorização | Identidade e permissões são responsabilidades diferentes | Login atual é demonstrativo; autenticação persistente e autorização por perfil estão documentadas como futuras |
| Aula 13 — Validação, Erros e Upload | Validação obrigatória no servidor e tratamento de exceções | Cadastro valida campos e trata falhas; upload permanece fora do escopo atual |
| Aula 14 — Deploy e Publicação | Separação de ambientes, credenciais e configuração de URLs | Banco aceita variáveis de ambiente e o guia descreve a execução local; publicação exige revisão de produção e backup |

A organização sugerida nas aulas concentra a classe de conexão em
<code>app/Config/Database.php</code>. Este projeto preserva a estrutura
equivalente que já estava funcional: <code>config/database.php</code> contém
somente os valores de ambiente, enquanto <code>core/Database.php</code>
centraliza a criação e a reutilização do PDO. As responsabilidades são as
mesmas, mas ficam separadas entre configuração e infraestrutura.

---

## 3️⃣ Cadastro e Listagem de Alunos

### Campos persistidos

| Campo | Tipo no MySQL | Regra |
| --- | --- | --- |
| <code>id</code> | <code>INT UNSIGNED</code> | Chave primária com incremento automático |
| <code>nome</code> | <code>VARCHAR(120)</code> | Obrigatório |
| <code>email</code> | <code>VARCHAR(150)</code> | Obrigatório e único |
| <code>matricula</code> | <code>VARCHAR(30)</code> | Obrigatória e única |
| <code>turma</code> | <code>VARCHAR(50)</code> | Obrigatória |
| <code>criado_em</code> | <code>TIMESTAMP</code> | Preenchido automaticamente |

### Fluxo do cadastro

1. O usuário acessa <code>GET /alunos/criar</code>.
2. O formulário envia os dados para <code>POST /alunos/salvar</code>.
3. O controller normaliza e valida nome, e-mail, matrícula e turma.
4. O model verifica duplicidades com prepared statements.
5. O aluno é inserido no MySQL.
6. A aplicação responde com redirecionamento 303 para <code>/alunos</code>.
7. A listagem consulta novamente o banco e exibe a mensagem de sucesso.

Quando ocorre um erro, os valores válidos permanecem preenchidos e cada
mensagem aparece próxima ao campo correspondente. Falhas inesperadas recebem
uma mensagem genérica; detalhes técnicos ficam somente no log do PHP.

---

## 4️⃣ Rotas Disponíveis

| Método | Caminho | Finalidade |
| --- | --- | --- |
| <code>GET</code> | <code>/</code> | Página inicial |
| <code>GET</code> | <code>/dashboard</code> | Painel principal |
| <code>GET</code> | <code>/login</code> | Formulário de login demonstrativo |
| <code>POST</code> | <code>/login</code> | Criação da sessão demonstrativa |
| <code>GET</code> | <code>/logout</code> | Encerramento da sessão |
| <code>GET</code> | <code>/alunos</code> | Listagem consultada no MySQL |
| <code>GET</code> | <code>/alunos/criar</code> | Formulário de novo aluno |
| <code>POST</code> | <code>/alunos/salvar</code> | Validação e persistência |
| <code>GET</code> | <code>/professores</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/turmas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/disciplinas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/matriculas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/usuarios</code> | Estrutura inicial do módulo |

Rotas não cadastradas respondem com **404**. Métodos incompatíveis respondem
com **405** e informam os métodos permitidos. Erros internos não previstos
respondem com uma página **500** genérica.

---

## 5️⃣ Banco de Dados e PDO

O script versionado em
[<code>database/schema.sql</code>](database/schema.sql) cria o banco
<code>sistema_escolar</code> e a tabela <code>alunos</code> sem apagar bancos,
tabelas ou registros existentes.

~~~sql
CREATE DATABASE IF NOT EXISTS sistema_escolar
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistema_escolar;

CREATE TABLE IF NOT EXISTS alunos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    turma VARCHAR(50) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
~~~

### Configuração da conexão

O arquivo [<code>config/database.php</code>](config/database.php) lê:

| Variável | Padrão local | Finalidade |
| --- | --- | --- |
| <code>DB_HOST</code> | <code>127.0.0.1</code> | Servidor MySQL |
| <code>DB_PORT</code> | <code>3306</code> | Porta |
| <code>DB_NAME</code> | <code>sistema_escolar</code> | Banco da aplicação |
| <code>DB_USER</code> | <code>root</code> | Usuário local |
| <code>DB_PASS</code> | vazio | Senha local |

A classe <code>core/Database.php</code> fornece
<code>Database::connect()</code>. O método mantém a conexão em uma propriedade
estática e devolve a mesma instância PDO durante a requisição. A conexão
utiliza:

- <code>utf8mb4</code>;
- <code>PDO::ERRMODE_EXCEPTION</code>;
- <code>PDO::FETCH_ASSOC</code>;
- <code>PDO::ATTR_EMULATE_PREPARES = false</code>;
- uma única conexão reutilizada durante cada requisição.

O projeto não carrega arquivos <code>.env</code> automaticamente. Em ambientes
diferentes do padrão local, as variáveis devem ser definidas no sistema ou na
configuração do Apache/PHP.

---

## 6️⃣ Resultados e Validações

> [!IMPORTANT]
> As verificações abaixo foram executadas localmente. A persistência completa
> deve ser demonstrada em uma instalação XAMPP com credenciais MySQL válidas.

| Validação | Resultado |
| --- | --- |
| Sintaxe de todos os arquivos PHP | ✅ Aprovada |
| Sintaxe de <code>public/js/app.js</code> | ✅ Aprovada |
| Rotas GET obrigatórias | ✅ 200 ou redirecionamento esperado |
| Post/Redirect/Get do cadastro | ✅ Resposta 303 |
| Rota inexistente | ✅ 404 |
| Método HTTP incompatível | ✅ 405 com cabeçalho <code>Allow</code> |
| Valores preservados após validação | ✅ Aprovado |
| Falhas de banco sem vazamento de detalhes | ✅ Aprovado |
| SQL dentro das views | ✅ Nenhuma ocorrência |
| Dados simulados no model | ✅ Nenhuma ocorrência |
| <code>git diff --check</code> | ✅ Aprovado |
| Inserção e consulta com MySQL local | ⚠️ Pendente de credenciais válidas |

### Tratamentos implementados

- campos obrigatórios ausentes;
- e-mail inválido;
- e-mail duplicado;
- matrícula duplicada;
- falha de conexão com o banco;
- erro inesperado durante o cadastro;
- listagem vazia;
- rota inexistente;
- método HTTP não permitido.

---

## 7️⃣ Como Executar com XAMPP

### Pré-requisitos

- XAMPP com Apache, PHP 7.4 ou superior e MySQL;
- extensões PHP <code>PDO</code> e <code>pdo_mysql</code>;
- módulo Apache <code>mod_rewrite</code>;
- permissão <code>AllowOverride</code> para o arquivo
  <code>public/.htaccess</code>;
- navegador web atualizado.

### Instalação

1. Coloque ou clone o projeto em:

   ~~~text
   C:\xampp\htdocs\projeto-integrador
   ~~~

2. Inicie **Apache** e **MySQL** no painel do XAMPP.
3. Acesse o phpMyAdmin em
   [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
4. Importe [<code>database/schema.sql</code>](database/schema.sql).
5. Confira as variáveis do banco ou mantenha os padrões locais.
6. Abra:

   [http://localhost/projeto-integrador/public/](http://localhost/projeto-integrador/public/)

Também é possível clonar pela linha de comando:

~~~powershell
Set-Location C:\xampp\htdocs
git clone https://github.com/isa-csilva/projeto-integrador.git
Set-Location projeto-integrador
git switch master
~~~

### Demonstração de Create e Read

1. Acesse <code>/alunos</code>.
2. Clique em **Novo aluno**.
3. Informe nome, e-mail, matrícula e turma inéditos.
4. Envie o formulário e confirme a mensagem de sucesso.
5. Atualize a página e confirme que o registro continua listado.
6. Repita o e-mail com outra matrícula e confira a validação.
7. Repita a matrícula com outro e-mail e confira a validação.

Se uma rota interna retornar 404 do próprio Apache, verifique
<code>mod_rewrite</code>, <code>AllowOverride</code> e reinicie o serviço.

---

## 8️⃣ Estrutura do Projeto

~~~text
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
├── routes/
│   └── web.php
├── public/
│   ├── css/
│   ├── js/
│   ├── .htaccess
│   └── index.php
├── .gitattributes
├── .gitignore
├── CODE_OF_CONDUCT.md
├── CONTRIBUTING.md
├── LICENSE
└── README.md
~~~

---

## 9️⃣ Decisões Técnicas e Limitações

### Decisões técnicas

- **MVC sem framework:** mantém a arquitetura visível e adequada ao contexto
  acadêmico.
- **Prepared statements reais:** evita a concatenação de dados do formulário
  no SQL.
- **Conexão centralizada:** reutiliza uma instância PDO durante a requisição.
- **Post/Redirect/Get:** impede o reenvio acidental após o cadastro.
- **Restrições únicas no MySQL:** protegem e-mail e matrícula mesmo em
  requisições concorrentes.
- **Escape na saída:** reduz o risco de XSS nas views.
- **Erros genéricos:** detalhes do banco não aparecem na interface.

### Limitações atuais

- o login é demonstrativo e ainda não consulta usuários no banco;
- não há controle de autorização por perfil;
- a proteção CSRF dos formulários ainda será adicionada em uma etapa de
  segurança;
- edição e exclusão de alunos pertencem à Parcial 4;
- upload de fotos e documentos ainda não foi implementado;
- os demais módulos possuem somente a estrutura inicial;
- a validação final da persistência depende do MySQL configurado no XAMPP.

### Próximas etapas

- implementar Update e Delete de alunos;
- adicionar autenticação persistente e senhas com hash;
- proteger rotas por sessão e perfil;
- desenvolver os CRUDs dos demais módulos;
- criar DER/MER e ampliar os relacionamentos do banco;
- adicionar testes automatizados.

---

## 🔐 Segurança

- Nunca versione senhas reais ou dados pessoais.
- Não adicione arquivos <code>.env</code>, logs ou uploads ao Git.
- Use credenciais próprias para cada ambiente.
- Mantenha <code>display_errors</code> desabilitado em produção.
- Consulte os detalhes técnicos somente nos logs do PHP.

---

## 🤝 Contribuição e Comunidade

Antes de enviar mudanças, consulte o [guia de contribuição](CONTRIBUTING.md) e
o [Código de Conduta](CODE_OF_CONDUCT.md). O código e a documentação autoral do
projeto são disponibilizados sob a [Licença MIT](LICENSE); marcas, avatares,
materiais da disciplina e outros conteúdos de terceiros permanecem sujeitos
aos direitos de seus respectivos titulares.

---

## 🔗 Repositório

- **GitHub:** [isa-csilva/projeto-integrador](https://github.com/isa-csilva/projeto-integrador)
- **Branch principal:** <code>master</code>
- **Licença:** [MIT](LICENSE)
- **Tecnologias obrigatórias:** PHP, MVC, MySQL, PDO, Git/GitHub e XAMPP

<p align="center">
  <strong>Projeto desenvolvido para a disciplina de Projeto e Implementação de Sistemas para Web II</strong>
</p>

<img width="100%" src="https://capsule-render.vercel.app/api?type=waving&amp;color=0f766e&amp;height=120&amp;section=footer" alt="Rodapé decorativo"/>
