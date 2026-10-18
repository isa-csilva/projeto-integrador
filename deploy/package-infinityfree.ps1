[CmdletBinding()]
param(
    [switch]$PassThru
)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot

# Lista fechada: credenciais locais, .env, backups, certificados, scripts de
# administração e arquivos adicionados por engano não entram na publicação.
$runtimeFiles = @(
    'app/Controllers/AlunoController.php'
    'app/Controllers/AuthController.php'
    'app/Controllers/DashboardController.php'
    'app/Controllers/ErrorController.php'
    'app/Controllers/HomeController.php'
    'app/Controllers/ModuloController.php'
    'app/Controllers/UsuarioController.php'
    'app/Models/Aluno.php'
    'app/Models/Usuario.php'
    'app/Views/alunos/_form.php'
    'app/Views/alunos/create.php'
    'app/Views/alunos/delete.php'
    'app/Views/alunos/edit.php'
    'app/Views/alunos/index.php'
    'app/Views/auth/login.php'
    'app/Views/dashboard/index.php'
    'app/Views/errors/403.php'
    'app/Views/errors/404.php'
    'app/Views/errors/405.php'
    'app/Views/errors/500.php'
    'app/Views/home/index.php'
    'app/Views/layouts/main.php'
    'app/Views/modulos/show.php'
    'app/Views/usuarios/create.php'
    'app/Views/usuarios/index.php'
    'config/database.php'
    'core/Auth.php'
    'core/Controller.php'
    'core/Database.php'
    'core/Router.php'
    'core/helpers.php'
    'routes/web.php'
)

$publicFiles = @('css/style.css', 'js/app.js')
$requiredFiles = $runtimeFiles + @(
    'public/index.php'
    'config/database.example.php'
    'database/schema-hosted.sql'
    'deploy/infinityfree/index.php'
    'deploy/infinityfree/.htaccess'
    'deploy/infinityfree/private.htaccess'
) + @($publicFiles | ForEach-Object { 'public/' + $_ })

foreach ($relativePath in $requiredFiles) {
    if (-not (Test-Path -LiteralPath (Join-Path $projectRoot $relativePath) -PathType Leaf)) {
        throw "Arquivo necessário ao pacote ausente: $relativePath"
    }
}

$packageName = 'infinityfree-' + (Get-Date -Format 'yyyyMMdd-HHmmss') + '-' + [Guid]::NewGuid().ToString('N').Substring(0, 8)
$packageRoot = Join-Path (Join-Path $projectRoot 'dist') $packageName
$htdocs = Join-Path $packageRoot 'htdocs'
$privateRoot = Join-Path $htdocs 'private'
$archivePath = Join-Path $packageRoot 'htdocs.zip'

# Cada execução cria outra pasta. Nenhum pacote anterior é apagado ou sobrescrito.
New-Item -ItemType Directory -Path $privateRoot -Force | Out-Null

function Copy-PackageFile {
    param([string]$Source, [string]$Destination)
    New-Item -ItemType Directory -Path (Split-Path -Parent $Destination) -Force | Out-Null
    Copy-Item -LiteralPath $Source -Destination $Destination -Force
}

foreach ($relativePath in $runtimeFiles) {
    Copy-PackageFile (Join-Path $projectRoot $relativePath) (Join-Path $privateRoot $relativePath)
}

foreach ($relativePath in $publicFiles) {
    Copy-PackageFile (Join-Path (Join-Path $projectRoot 'public') $relativePath) (Join-Path $htdocs $relativePath)
}

Copy-PackageFile (Join-Path $projectRoot 'public/index.php') (Join-Path $privateRoot 'front-controller.php')
Copy-PackageFile (Join-Path $projectRoot 'config/database.example.php') (Join-Path $privateRoot 'config/database.local.php')
Copy-PackageFile (Join-Path $PSScriptRoot 'infinityfree/index.php') (Join-Path $htdocs 'index.php')
Copy-PackageFile (Join-Path $PSScriptRoot 'infinityfree/.htaccess') (Join-Path $htdocs '.htaccess')
Copy-PackageFile (Join-Path $PSScriptRoot 'infinityfree/private.htaccess') (Join-Path $privateRoot '.htaccess')
Copy-PackageFile (Join-Path $projectRoot 'database/schema-hosted.sql') (Join-Path $packageRoot 'schema-hosted.sql')

# ZipFile inclui .htaccess. O SQL fica fora do ZIP e da pasta pública htdocs.
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory(
    $htdocs, $archivePath, [System.IO.Compression.CompressionLevel]::Optimal, $true
)

if ($PassThru) {
    [PSCustomObject]@{
        Directory = $packageRoot
        Htdocs = $htdocs
        Archive = $archivePath
        Schema = (Join-Path $packageRoot 'schema-hosted.sql')
    }
} else {
    Write-Host "Pacote criado: $packageRoot"
    Write-Host 'Configure htdocs/private/config/database.local.php no pacote extraído com os dados do MySQL do InfinityFree.'
    Write-Host 'Instale o SSL antes de enviar o conteúdo de htdocs por FTP, incluindo os arquivos .htaccess.'
    Write-Host 'Importe schema-hosted.sql pelo phpMyAdmin; não envie esse SQL para htdocs.'
    Write-Host 'O ZIP contém a configuração de exemplo. Alterar o arquivo extraído não altera o ZIP.'
}
