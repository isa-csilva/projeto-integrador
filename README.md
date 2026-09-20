<img width="100%" src="https://capsule-render.vercel.app/api?type=waving&amp;color=0f766e&amp;height=120&amp;section=header" alt="Cabeçalho decorativo"/>

<h1 align="center">
  🏫
  <br/>
  Sistema de Gestão Escolar
</h1>

<p align="center">
  Aplicação web acadêmica para gerenciar cadastros escolares com CRUD completo,
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
  <img alt="Parcial 4" src="https://img.shields.io/badge/Parcial%204-conclu%C3%ADda-2ea44f"/>
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
Nesta etapa, a aplicação entrega a base arquitetural e o gerenciamento completo
de alunos, com cadastro, consulta, edição e exclusão.

A solução implementa:

- front controller e roteamento por método HTTP;
- separação entre controllers, models e views;
- cadastro de alunos persistido no MySQL;
- listagem ordenada dos alunos cadastrados;
- edição dos dados de alunos existentes;
- exclusão mediante uma tela explícita de confirmação;
- validação dos campos obrigatórios e do formato de e-mail;
- detecção de e-mail e matrícula duplicados;
- mensagens flash e fluxo Post/Redirect/Get;
- proteção CSRF nas operações de escrita do módulo de alunos;
- autenticação persistente com hash de senha e perfis de acesso;
- cadastro e listagem de usuários por administradores;
- logout por POST com CSRF e expiração da sessão por inatividade;
- deploy opcional no Render com Docker e MySQL externo com TLS;
- páginas de erro 403, 404, 405 e 500;
- interface responsiva em português brasileiro; e
- suporte à instalação em uma subpasta do <code>htdocs</code>.

### Situação das entregas

| Entrega | Situação | Escopo |
| --- | --- | --- |
| Parcial 2 — Estrutura MVC e Rotas | ✅ Concluída | MVC, controllers, views, front controller, rotas e páginas de erro |
| Parcial 3 — CRUD Inicial | ✅ Implementada | PDO, schema MySQL e operações Create e Read de alunos |
| Parcial 4 — CRUD Completo | ✅ Concluída | Create, Read, Update e Delete de alunos, com validações e mensagens |
| Parcial 5 — Autenticação e autorização | ✅ Implementada | Login persistente, perfis, sessão, logout com CSRF e cadastro de usuários |

> [!NOTE]
> A entidade principal **Aluno** possui CRUD completo. Upload de arquivos e os
> CRUDs dos demais módulos permanecem como funcionalidades futuras.

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
    ├── coordena validação, mensagens e redirecionamentos
    ├── chama o model Aluno ──► normalização, validação e persistência
    │                              │
    │                              ▼
    │                          Database ──► MySQL
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
| Controller | <code>app/Controllers</code> | Coordena validação, mensagens e fluxo HTTP |
| Model | <code>app/Models/Aluno.php</code> | Normaliza, valida e executa as operações de dados com PDO |
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
| Aula 08 — CRUD Update e Delete | Atualização, exclusão segura e confirmação | Model, Controller, Views e rotas implementam edição e exclusão de alunos |
| Aula 09 — Requisições e Respostas HTTP | GET para consulta, POST para escrita e validação no servidor | Rotas distinguem os verbos; Create, Update e Delete usam POST, CSRF, PRG e mensagens amigáveis |
| Aula 10 — Boas Práticas e Segurança | Responsabilidade única, prepared statements, escape e erros seguros | SQL fica no Model, <code>e()</code> protege a saída e detalhes técnicos vão para o log |
| Aula 11 — Sessões e Cookies | Estado da navegação e encerramento correto da sessão | Sessão inicia antes da saída, armazena mensagens flash e é regenerada no login e periodicamente |
| Aula 12 — Autenticação e Autorização | Identidade e permissões são responsabilidades diferentes | Login consulta usuários no MySQL, verifica senha com hash e aplica permissões por perfil |
| Aula 13 — Validação, Erros e Upload | Validação obrigatória no servidor e tratamento de exceções | Cadastro valida campos e trata falhas; upload permanece fora do escopo atual |
| Aula 14 — Deploy e Publicação | Separação de ambientes, credenciais e configuração de URLs | Banco aceita variáveis de ambiente e o guia descreve a execução local; deploy opcional usa Render, Docker e MySQL externo com TLS |

A organização sugerida nas aulas concentra a classe de conexão em
<code>app/Config/Database.php</code>. Este projeto preserva a estrutura
equivalente que já estava funcional: <code>config/database.php</code> contém
somente os valores de ambiente, enquanto <code>core/Database.php</code>
centraliza a criação e a reutilização do PDO. As responsabilidades são as
mesmas, mas ficam separadas entre configuração e infraestrutura.

---

## 3️⃣ Gerenciamento de Alunos

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
3. O model normaliza e valida nome, e-mail, matrícula e turma; o controller
   coordena o fluxo.
4. O model verifica duplicidades com prepared statements.
5. O aluno é inserido no MySQL.
6. A aplicação responde com redirecionamento 303 para <code>/alunos</code>.
7. A listagem consulta novamente o banco e exibe a mensagem de sucesso.

Quando ocorre um erro, os valores válidos permanecem preenchidos e cada
mensagem aparece próxima ao campo correspondente. Falhas inesperadas recebem
uma mensagem genérica; detalhes técnicos ficam somente no log do PHP.

### Fluxo da edição

1. O usuário escolhe **Editar** na listagem e acessa
   <code>GET /alunos/{id}/editar</code>.
2. O formulário é preenchido com os dados atuais e envia
   <code>POST /alunos/{id}/atualizar</code>.
3. O model valida os campos e verifica duplicidades, desconsiderando o próprio
   registro.
4. A atualização usa prepared statement e responde com redirecionamento 303,
   mesmo quando os valores enviados não foram alterados.
5. A listagem apresenta a mensagem de sucesso ou o formulário apresenta os
   erros com os valores preservados.

### Fluxo da exclusão

1. O usuário escolhe **Excluir** e acessa uma página de confirmação por GET.
2. A confirmação mostra os dados do aluno sem executar qualquer exclusão.
3. Somente o formulário <code>POST /alunos/{id}/excluir</code>, protegido por
   token CSRF, solicita a remoção ao model.
4. A aplicação redireciona para a listagem e exibe uma mensagem de sucesso ou
   erro.

---

## 4️⃣ Rotas Disponíveis

| Método | Caminho | Finalidade |
| --- | --- | --- |
| <code>GET</code> | <code>/</code> | Página inicial |
| <code>GET</code> | <code>/dashboard</code> | Painel principal |
| <code>GET</code> | <code>/login</code> | Formulário de login |
| <code>POST</code> | <code>/login</code> | Autenticação com e-mail e senha do banco |
| <code>POST</code> | <code>/logout</code> | Encerramento da sessão |
| <code>GET</code> | <code>/alunos</code> | Listagem consultada no MySQL |
| <code>GET</code> | <code>/alunos/criar</code> | Formulário de novo aluno |
| <code>POST</code> | <code>/alunos/salvar</code> | Validação e persistência |
| <code>GET</code> | <code>/alunos/{id}/editar</code> | Formulário preenchido para edição |
| <code>POST</code> | <code>/alunos/{id}/atualizar</code> | Validação e atualização |
| <code>GET</code> | <code>/alunos/{id}/excluir</code> | Confirmação da exclusão |
| <code>POST</code> | <code>/alunos/{id}/excluir</code> | Exclusão confirmada do registro |
| <code>GET</code> | <code>/professores</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/turmas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/disciplinas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/matriculas</code> | Estrutura inicial do módulo |
| <code>GET</code> | <code>/usuarios</code> | Listagem de usuários (administrador) |
| <code>GET</code> | <code>/usuarios/criar</code> | Cadastro de usuário (administrador) |
| <code>POST</code> | <code>/usuarios/salvar</code> | Persistência de usuário com hash e CSRF |

Rotas não cadastradas respondem com **404**. Métodos incompatíveis respondem
com **405** e informam os métodos permitidos. Erros internos não previstos
respondem com uma página **500** genérica.

---

## 5️⃣ Banco de Dados e PDO

O script versionado em
[<code>database/schema.sql</code>](database/schema.sql) cria o banco
<code>sistema_escolar</code> e as tabelas <code>alunos</code> e <code>usuarios</code> sem apagar bancos,
tabelas ou registros existentes.

Trecho da tabela principal (o arquivo completo também cria `usuarios`):

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
| <code>DB_SSL_CA</code> | vazio | Caminho do certificado CA para TLS com verificação do servidor |
| <code>SESSION_SECURE_COOKIE</code> | automático pelo HTTPS do Apache | Use `1` no Render para cookies Secure atrás do proxy HTTPS |
| <code>AUTH_IDLE_TIMEOUT</code> | <code>1800</code> | Expiração por inatividade em segundos (mínimo 60) |

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

A revisão atual cobre MVC, rotas, autenticação, autorização, formulários, models,
views, assets, schema e configuração de deploy. Para repetir as verificações:

~~~powershell
rg --files -g "*.php" | ForEach-Object { php -l $_ }
node --check public/js/app.js
php tests/smoke.php
git diff --check
~~~

O teste de smoke não acessa dados reais: valida permissões, rotas, CSRF e
configuração local. O teste completo de persistência requer MySQL ativo e
`pdo_mysql`. Build Docker e deploy remoto devem ser verificados no ambiente
correspondente; sintaxe aprovada não comprova conectividade ou persistência.

Na revisão de 20/09/2026: sintaxe dos 36 arquivos PHP, sintaxe do JavaScript,
24 verificações de smoke e `git diff --check` aprovados. O PHP CLI disponível
não tinha `pdo_mysql` habilitado e o Docker Engine estava desligado; portanto,
persistência MySQL, conexão TLS real e build da imagem não foram executados.
Nenhum serviço remoto foi criado ou publicado nesta revisão.

### Tratamentos implementados

- campos obrigatórios ausentes;
- e-mail inválido;
- e-mail duplicado;
- matrícula duplicada;
- identificador inválido ou registro inexistente;
- falha de conexão com o banco;
- erro inesperado durante cadastro, atualização ou exclusão;
- atualização sem alteração dos valores;
- exclusão somente após confirmação por POST;
- requisição de escrita sem token CSRF válido;
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
6. Crie o administrador inicial conforme a seção abaixo.
7. Abra:

   [http://localhost/projeto-integrador/public/](http://localhost/projeto-integrador/public/)

Também é possível clonar pela linha de comando:

~~~powershell
Set-Location C:\xampp\htdocs
git clone https://github.com/isa-csilva/projeto-integrador.git
Set-Location projeto-integrador
git switch master
~~~

### Primeiro administrador e perfis

No PowerShell, na raiz do projeto, usando o PHP do XAMPP no PATH:

~~~powershell
$env:APP_ADMIN_NAME = "Administrador"
$env:APP_ADMIN_EMAIL = "admin@escola.test"
$adminSecret = Read-Host "Senha inicial (mínimo 8 caracteres)" -AsSecureString
$env:APP_ADMIN_PASSWORD = [System.Net.NetworkCredential]::new("", $adminSecret).Password
try { php database/create_admin.php } finally { Remove-Item Env:APP_ADMIN_PASSWORD }
~~~

O script cria o usuário ou redefine nome, senha e perfil do e-mail informado
para administrador ativo. Execute apenas para a criação ou recuperação
intencional da conta; não o configure para rodar a cada deploy. Não existe senha
padrão. Depois, entre em `/login`.

| Perfil | Consultar alunos | Cadastrar, editar e excluir alunos | Listar e cadastrar usuários |
| --- | --- | --- | --- |
| Administrador | Sim | Sim | Sim |
| Secretaria | Sim | Sim | Não |
| Consulta | Sim | Não | Não |

As permissões são verificadas no servidor. A sessão expira após 30 minutos de
inatividade por padrão; login regenera seu identificador e o token CSRF.

### Demonstração do CRUD completo

1. Faça login como administrador ou secretaria e acesse <code>/alunos</code>.
2. Clique em **Novo aluno**.
3. Informe nome, e-mail, matrícula e turma inéditos.
4. Envie o formulário e confirme a mensagem de sucesso.
5. Atualize a página e confirme que o registro continua listado.
6. Repita o e-mail com outra matrícula e confira a validação.
7. Repita a matrícula com outro e-mail e confira a validação.
8. Clique em **Editar**, altere um campo e confirme a mensagem de sucesso.
9. Atualize a página e confira se a alteração permaneceu no banco.
10. Clique em **Excluir**, cancele uma vez e confirme que o registro permanece.
11. Abra novamente a confirmação, conclua a exclusão e confira a mensagem de
    sucesso e a remoção da listagem.

Se uma rota interna retornar 404 do próprio Apache, verifique
<code>mod_rewrite</code>, <code>AllowOverride</code> e reinicie o serviço.

---

## ☁️ Deploy opcional: Render + Aiven MySQL

O mesmo projeto continua funcionando no XAMPP. Para uma demonstração online,
o [Render aceita PHP por Docker](https://render.com/docs/docker) e oferece
[Web Services gratuitos](https://render.com/docs/free). O banco fica separado,
no [plano gratuito de MySQL da Aiven](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier).
Os planos foram consultados em 20/09/2026; confira a disponibilidade na sua conta.
O serviço publica a aplicação completa (PHP renderiza o frontend), sem exigir
um frontend separado ou mudanças de CORS.

### 1. Preparar o banco remoto

1. Na Aiven, crie um serviço **MySQL no plano Free**, sem selecionar trial pago.
2. Copie host, porta, nome do banco, usuário e senha do painel. Use o nome real
   do banco fornecido, normalmente `defaultdb`.
3. Baixe o certificado CA do serviço como `ca.pem` e mantenha-o fora do Git.
4. No PowerShell local, configure a conexão (a porta abaixo é um exemplo):

~~~powershell
$env:DB_HOST = "host-fornecido-pela-aiven"
$env:DB_PORT = "12345"
$env:DB_NAME = "defaultdb"
$env:DB_USER = "avnadmin"
$dbSecret = Read-Host "Senha do banco remoto" -AsSecureString
$env:DB_PASS = [System.Net.NetworkCredential]::new("", $dbSecret).Password
$env:DB_SSL_CA = "C:\caminho\ca.pem"
php database/migrate.php
~~~

Use PHP com `pdo_mysql` habilitado. `migrate.php` cria as tabelas do schema
no banco de `DB_NAME`, sem executar `CREATE DATABASE` nem `USE sistema_escolar`.
É repetível e não apaga registros; não modifica a estrutura de tabelas já
existentes. Para um banco com dados, faça backup antes de executar alterações.
Após confirmar o sucesso, execute `database/create_admin.php` conforme o guia
local, mantendo as variáveis do banco remoto neste terminal.

Ao terminar, feche esse terminal ou remova as variáveis para voltar ao banco local:

~~~powershell
'DB_HOST','DB_PORT','DB_NAME','DB_USER','DB_PASS','DB_SSL_CA' | ForEach-Object {
    Remove-Item "Env:$_" -ErrorAction SilentlyContinue
}
~~~

### 2. Publicar no Render

1. Envie estes arquivos ao seu repositório GitHub.
2. No Render, use **New → Blueprint**, conecte o repositório e selecione a branch
   com estas alterações. O [render.yaml](render.yaml) configura Docker e plano Free.
3. Preencha `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS` com os dados
   da Aiven. Não coloque senhas no YAML ou no Dockerfile.
4. Em **Environment → Secret Files**, adicione `ca.pem` com o conteúdo integral
   do certificado baixado. O caminho configurado é `/etc/secrets/ca.pem`.
5. Salve e faça o deploy/redeploy. O Apache escuta na porta 80 (`PORT=80`) e
   publica somente `public/`. O health check `/login` verifica PHP/HTTP, sem
   garantir a disponibilidade do banco.
6. Acesse `https://<servico>.onrender.com/login`, entre com o administrador
   criado e teste cadastro, edição, consulta e exclusão de um aluno fictício.

Não há migração nem redefinição de administrador automática no startup. O
preparo pelo terminal local evita depender de shell remoto do plano gratuito.
`SESSION_SECURE_COOKIE=1` protege os cookies no HTTPS do Render mesmo com
HTTP entre o proxy e o Apache, sem confiar em cabeçalhos enviados pelo cliente.
`DB_SSL_CA` habilita TLS e verificação do certificado do MySQL; CA ausente ou
inválida deve ser corrigida, nunca contornada desabilitando a verificação.

### Testar a imagem localmente (opcional)

Com Docker Desktop ativo:

~~~powershell
docker build -t sistema-escolar .
docker run --rm -p 8080:80 -e DB_HOST=host.docker.internal -e DB_PORT=3306 -e DB_NAME=sistema_escolar -e DB_USER -e DB_PASS sistema-escolar
~~~

Abra `http://localhost:8080`. Para login/CRUD, configure `DB_USER` e `DB_PASS`
no terminal e permita a conexão do container ao MySQL local. Não use
`SESSION_SECURE_COOKIE=1` neste teste por HTTP. O XAMPP continua sendo a opção
local principal e não depende do Docker.

### Limites da opção gratuita

- O Render Free suspende o serviço após 15 minutos sem tráfego; o primeiro
  acesso pode demorar. Há cotas de uso, build e tráfego compartilhadas na conta.
- O disco do Render é efêmero. Sessões em arquivo podem ser perdidas em
  reinícios/deploys, exigindo novo login; os dados persistem no MySQL externo.
- Não armazene o MySQL ou futuros uploads no disco desse container.
- Aiven Free tem recursos limitados e pode desligar serviços inativos. Verifique
  os limites atuais nos links oficiais acima. Essa configuração destina-se a
  demonstrações acadêmicas, com dados fictícios.

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
│   │   ├── ModuloController.php
│   │   └── UsuarioController.php
│   ├── Models/
│   │   ├── Aluno.php
│   │   └── Usuario.php
│   └── Views/
│       ├── alunos/
│       ├── auth/
│       ├── dashboard/
│       ├── errors/
│       ├── home/
│       ├── layouts/
│       ├── modulos/
│       └── usuarios/
├── config/
│   └── database.php
├── core/
│   ├── Auth.php
│   ├── Controller.php
│   ├── Database.php
│   ├── Router.php
│   └── helpers.php
├── database/
│   ├── create_admin.php
│   ├── migrate.php
│   └── schema.sql
├── deploy/
│   ├── apache.conf
│   └── php.ini
├── tests/
│   └── smoke.php
├── Dockerfile
├── .dockerignore
├── render.yaml
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
- **Post/Redirect/Get:** impede o reenvio acidental após cadastro, edição ou
  exclusão.
- **Confirmação antes da exclusão:** uma página GET informa qual registro será
  removido, enquanto somente o formulário POST executa a operação.
- **Proteção CSRF:** tokens de sessão são obrigatórios nas operações de escrita
  do módulo de alunos.
- **Restrições únicas no MySQL:** protegem e-mail e matrícula mesmo em
  requisições concorrentes.
- **Escape na saída:** reduz o risco de XSS nas views.
- **Erros genéricos:** detalhes do banco não aparecem na interface.

### Limitações atuais

- não há recuperação de senha por e-mail nem limitação de tentativas de login;
- alterações de perfil/inativação feitas diretamente no banco só afetam novos logins;
- usuários têm cadastro e listagem, sem edição/exclusão pela interface;
- upload de fotos e documentos ainda não foi implementado;
- os demais módulos possuem somente a estrutura inicial;
- a validação final da persistência depende do MySQL configurado no XAMPP.

### Próximas etapas

- adicionar limitação de tentativas de login e recuperação de senha;
- revalidar usuários ativos e permissões durante sessões existentes;
- desenvolver os CRUDs dos demais módulos;
- criar DER/MER e ampliar os relacionamentos do banco;
- ampliar testes de integração com MySQL.

---

## 🔐 Segurança

- Nunca versione senhas reais ou dados pessoais.
- Não adicione arquivos <code>.env</code>, logs ou uploads ao Git.
- Use credenciais próprias para cada ambiente.
- Preserve a validação CSRF em todo novo formulário que altere dados.
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
