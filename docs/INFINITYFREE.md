# Publicar no InfinityFree

O código continua rodando no XAMPP. Na hospedagem, PHP e MySQL ficam no
InfinityFree. A configuração anterior do Render foi removida do repositório;
nenhuma conta, serviço ou banco remoto foi apagado.

## 1. Criar a hospedagem e o banco

1. Crie sua conta em [InfinityFree](https://www.infinityfree.com/) e uma conta de
   hospedagem gratuita. Escolha um subdomínio ou associe seu domínio.
2. Aguarde a conta ficar ativa e abra o painel de controle.
3. Em **MySQL Databases**, crie um banco, por exemplo `escolar`.
4. Anote o **MySQL Hostname**, usuário e nome completo do banco com o prefixo
   da conta (por exemplo, `if0_12345678_escolar`).
5. Use a senha da **conta de hospedagem**, indicada no painel. Ela não é a senha
   do administrador do sistema escolar nem necessariamente a senha da área do cliente.

Os nomes abaixo são exemplos. Use os dados reais do seu painel, incluindo o
hostname MySQL; não use `localhost` ou o hostname antigo da Aiven.
[Guia oficial de conexão MySQL](https://forum.infinityfree.com/t/how-to-connect-with-mysql/49340).

## 2. Criar as tabelas pelo phpMyAdmin

1. No painel, abra o **phpMyAdmin** do banco criado.
2. Selecione esse banco na coluna da esquerda.
3. Abra **Importar**, selecione `database/schema-hosted.sql` deste projeto e execute.
4. Confirme a existência das tabelas `alunos` e `usuarios`.

`schema-hosted.sql` cria apenas as tabelas, sem `CREATE DATABASE` ou `USE`.
É repetível, não exclui registros e não altera tabelas já existentes. O schema
local `database/schema.sql` continua disponível para XAMPP.

O MySQL gratuito do InfinityFree não aceita conexão do seu computador. Use
o phpMyAdmin para importar o SQL; não execute `migrate.php` ou `create_admin.php`
localmente esperando conectar ao banco do provedor.
[Restrição oficial de acesso remoto](https://forum.infinityfree.com/t/connecting-to-mysql-from-an-external-application/49339).

## 3. Criar o administrador sem conexão remota

Se você vai importar uma instalação existente com usuários, siga a seção 7
e preserve essas contas. Para um banco novo, abra o PowerShell **na raiz do
projeto**, com PHP no PATH, e execute:

```powershell
$env:APP_ADMIN_NAME = "Mateus"
$env:APP_ADMIN_EMAIL = "seu-email@exemplo.com"
$adminSecret = Read-Host "Digite a senha do administrador (mínimo 8 caracteres)" -AsSecureString
$env:APP_ADMIN_PASSWORD = [System.Net.NetworkCredential]::new("", $adminSecret).Password
try {
    php database/export_admin.php
} finally {
    Remove-Item Env:APP_ADMIN_PASSWORD -ErrorAction SilentlyContinue
}
```

O texto entre aspas no `Read-Host` é a pergunta; a senha é o que você digita
depois. Esse comando **não acessa nenhum banco**, mesmo que existam variáveis
antigas `DB_HOST` ou `DB_SSL_CA` no terminal. Ele cria
`database/backups/admin-inicial.sql`, contendo o hash da senha.

Importe esse arquivo pelo phpMyAdmin **no mesmo banco da etapa 2**. Depois,
confirme que há uma linha em `usuarios` com seu e-mail e perfil `administrador`.
Não envie esse SQL para `htdocs`, não o publique no Git e não use hashes gerados
por sites de terceiros. O script recusa sobrescrever um arquivo existente; para
uma nova exportação, use `php database/export_admin.php admin-novo.sql`.
Importar um e-mail já existente retorna duplicidade e não redefine a conta.

## 4. Gerar e configurar o pacote

Execute na raiz do projeto:

```powershell
powershell -ExecutionPolicy Bypass -File deploy/package-infinityfree.ps1
```

O comando cria uma **nova** pasta `dist/infinityfree-<identificador>/` e informa
os caminhos do diretório de upload e do ZIP. A pasta contém uma estrutura como:

```text
infinityfree-<identificador>/
├── schema-hosted.sql          # para importar, não enviar ao site
└── htdocs/
    ├── .htaccess
    ├── index.php
    ├── css/
    ├── js/
    └── private/
        ├── .htaccess
        ├── front-controller.php
        ├── app/
        ├── config/
        │   ├── database.php
        │   └── database.local.php
        ├── core/
        └── routes/
```

O pacote usa uma lista explícita dos arquivos necessários. Não copia `.env`,
credenciais locais, certificados, dumps, scripts administrativos ou o Git.
O `front-controller.php` é uma cópia do mesmo `public/index.php` usado localmente.

Abra **o arquivo gerado** `htdocs/private/config/database.local.php` e substitua
os exemplos pelos dados da etapa 1:

```php
return array(
    'host' => 'sqlXXX.infinityfree.com',
    'port' => 3306,
    'name' => 'if0_XXXXXXXX_escolar',
    'user' => 'if0_XXXXXXXX',
    'pass' => 'SENHA_DA_CONTA_DE_HOSPEDAGEM',
    'ssl_ca' => ''
);
```

Preserve `<?php` e a proteção que já existem no arquivo. Se a senha contiver
apóstrofo ou barra invertida, escape-os como `\'` e `\\` dentro da string PHP.
Não altere `config/database.php` do repositório. O arquivo privado prevalece
sobre as variáveis de ambiente; a senha não é impressa pela aplicação.

O MySQL da hospedagem usa os dados fornecidos pelo InfinityFree. Essa
configuração não usa o certificado `ca.pem` da Aiven. O HTTPS do site é
configurado separadamente, conforme a próxima etapa.

O ZIP é uma cópia do momento da geração: se editar `database.local.php` na pasta
depois, envie os arquivos dessa pasta ou atualize esse arquivo no servidor após
extrair o ZIP. Guarde qualquer cópia com credenciais fora do Git.

## 5. Ativar HTTPS e enviar os arquivos

1. Verifique HTTPS no domínio. Subdomínios gratuitos usam o certificado do
   provedor; para domínio próprio, siga a instalação no painel **Free SSL
   Certificates**. [Guia oficial de HTTPS](https://forum.infinityfree.com/t/how-to-get-free-ssl-https-on-infinityfree/49323).
2. Abra o File Manager ou conecte um cliente FTP usando as informações da conta.
   [Guia de upload por FTP](https://forum.infinityfree.com/t/how-to-upload-files-with-ftp/49306).
3. Localize a pasta **htdocs do domínio escolhido** (pode estar dentro da pasta
   do domínio). Faça backup de arquivos existentes antes de substituí-los.
4. Envie **o conteúdo** da pasta `htdocs` gerada, incluindo os dois `.htaccess`.
   Não envie a pasta do repositório inteiro nem crie `htdocs/htdocs`.
5. Se houver um `index.html` padrão do provedor, renomeie/remova somente esse
   arquivo padrão após confirmar que não contém conteúdo seu.
6. Acesse `https://SEU-DOMINIO/login`, sem `/public`, e entre com a conta da etapa 3.

O `.htaccess` do pacote encaminha as rotas ao PHP, força HTTPS e bloqueia
requisições para `private/`. O segundo `.htaccess` bloqueia todo acesso HTTP à
pasta interna. Eles são necessários porque os arquivos internos ficam dentro
da raiz da hospedagem. Não use o servidor embutido `php -S` para publicar esse
pacote: ele não aplica essas regras do Apache.

## 6. Validar e atualizar

- Abra `/login`, entre, cadastre um aluno fictício, atualize a página, edite e exclua.
- Teste um usuário de consulta: ele não pode cadastrar alunos ou acessar usuários.
- Abra `/private/config/database.local.php` e `/private/`: devem negar o acesso
  (403/404), nunca revelar conteúdo ou listar diretórios.
- Confira se `/css/style.css` carrega e se o logout encerra a sessão.

Em futuras versões, gere outro pacote e atualize os arquivos por FTP. **Preserve
o `private/config/database.local.php` real do servidor**: o novo pacote contém
apenas exemplos. Não importe o SQL do administrador novamente para atualizar
o aplicativo. Nenhuma migração ou redefinição de senha roda automaticamente.

## 7. Preservar dados da instalação anterior

Trocar o provedor não transfere automaticamente alunos, usuários ou senhas.
Para continuar usando os registros locais ou da Aiven:

1. Faça uma exportação SQL das tabelas `alunos` e `usuarios` na origem, incluindo
   seus dados. Preserve `id`, `senha_hash`, perfil e estado ativo dos usuários.
2. Importe primeiro a estrutura da etapa 2 em um banco **novo e vazio** no destino.
3. Exporte/importe os dados sem `CREATE DATABASE`, `USE`, `DROP TABLE` ou outro
   comando que troque/apague o banco. O hash existente mantém a mesma senha.
4. Não misture essa importação com o administrador da etapa 3 se ele já existir
   na origem; isso gera conflitos de ID ou e-mail.
5. Confirme contagens de registros, login e edição antes de desativar a origem.

Os scripts deste repositório não exportam nem importam dados reais automaticamente.
Render e Aiven continuam na sua conta até você optar por desativá-los.

## Diagnóstico

| Sintoma | Verificação |
| --- | --- |
| Site mostra exemplos ou erro de conexão | Preencha o arquivo privado **no servidor**, não só no computador |
| `Access denied` | Confira usuário e senha da conta de hospedagem |
| Banco não encontrado | Use o nome completo com prefixo `if0_...` |
| Falha ao conectar pelo PowerShell | Use phpMyAdmin; MySQL remoto não está disponível no plano gratuito |
| Tabela `usuarios` ausente | Importe `schema-hosted.sql` no banco configurado |
| E-mail ou senha inválidos | Confirme importação do administrador ou dos usuários anteriores |
| Erro sobre CA da Aiven | Verifique se o site acessado ainda é o antigo ou se `ssl_ca` foi mantido indevidamente |
| Rotas internas retornam 404 | Confirme `htdocs/.htaccess` e envio do conteúdo da pasta correta |
| CSS não carrega | Confirme `htdocs/css/style.css` e evite uma pasta `htdocs` duplicada |
| Erro ao abrir HTTPS | Termine a configuração do certificado do domínio antes do login |

As orientações dos provedores foram consultadas em 18/10/2026. A publicação real,
as permissões do Apache e a conexão ao MySQL do InfinityFree precisam ser
validadas na sua conta. O plano é adequado para demonstração acadêmica; as
limitações de segurança do aplicativo estão descritas no README.
