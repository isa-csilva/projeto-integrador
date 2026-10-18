[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$php = (Get-Command php -ErrorAction Stop).Source
$script:checks = 0

function Assert-Package {
    param([bool]$Condition, [string]$Message)
    if (-not $Condition) {
        throw $Message
    }
    $script:checks++
}

$package = & (Join-Path $projectRoot 'deploy/package-infinityfree.ps1') -PassThru
Assert-Package (Test-Path -LiteralPath $package.Schema -PathType Leaf) 'SQL para importação não foi gerado.'
Assert-Package (-not (Test-Path -LiteralPath (Join-Path $package.Htdocs 'schema-hosted.sql'))) 'SQL não pode ficar na pasta pública.'

Add-Type -AssemblyName System.IO.Compression.FileSystem
$archive = [System.IO.Compression.ZipFile]::OpenRead($package.Archive)
try {
    $entries = @($archive.Entries | Where-Object { $_.Name -ne '' })
    $paths = @($entries | ForEach-Object { $_.FullName.Replace('\', '/') })
    foreach ($required in @(
        'htdocs/index.php'
        'htdocs/.htaccess'
        'htdocs/css/style.css'
        'htdocs/js/app.js'
        'htdocs/private/.htaccess'
        'htdocs/private/front-controller.php'
        'htdocs/private/core/Database.php'
        'htdocs/private/app/Views/auth/login.php'
        'htdocs/private/config/database.php'
        'htdocs/private/config/database.local.php'
        'htdocs/private/routes/web.php'
    )) {
        Assert-Package ($paths -contains $required) "Arquivo obrigatório ausente do ZIP: $required"
    }

    foreach ($path in $paths) {
        $allowed = $path -match '^htdocs/(index\.php|\.htaccess|css/style\.css|js/app\.js)$' -or
            $path -match '^htdocs/private/(front-controller\.php|\.htaccess|config/database(?:\.local)?\.php)$' -or
            $path -match '^htdocs/private/(app/(Controllers|Models|Views)|core|routes)/[a-zA-Z0-9_./-]+\.php$'
        Assert-Package $allowed "Arquivo inesperado no ZIP: $path"
        Assert-Package ($path -notmatch '(^|/)(\.git|\.env|tests|database|deploy)(/|$)|\.(sql|pem|key|bak|zip)$') "Arquivo sensível no ZIP: $path"
    }

    $configEntry = $entries | Where-Object { $_.FullName.Replace('\', '/') -eq 'htdocs/private/config/database.local.php' }
    $reader = [System.IO.StreamReader]::new($configEntry.Open())
    try {
        $configText = $reader.ReadToEnd()
    } finally {
        $reader.Dispose()
    }
    $exampleText = [System.IO.File]::ReadAllText((Join-Path $projectRoot 'config/database.example.php'))
    Assert-Package ($configText -ceq $exampleText) 'O pacote deve conter somente a configuração de exemplo, nunca credenciais locais.'
    Assert-Package ($configText.Contains('PREENCHA_A_SENHA_MYSQL')) 'O exemplo deve exigir configuração explícita da senha.'

    $privateEntry = $entries | Where-Object { $_.FullName.Replace('\', '/') -eq 'htdocs/private/.htaccess' }
    $reader = [System.IO.StreamReader]::new($privateEntry.Open())
    try {
        $privateRules = $reader.ReadToEnd()
    } finally {
        $reader.Dispose()
    }
    Assert-Package ($privateRules.Contains('Require all denied') -and $privateRules.Contains('Deny from all')) 'O diretório privado deve bloquear acesso HTTP.'
} finally {
    $archive.Dispose()
}

# Carrega o front controller real do pacote. Não usa banco nem credenciais.
# Um require ausente ou o ROOT_PATH errado impede a página de login de renderizar.
$phpTest = @'
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTPS'] = 'on';
ob_start();
require $argv[1];
$html = ob_get_clean();
foreach (array('href="/css/style.css"', 'src="/js/app.js"', 'action="/login"', 'name="_token"') as $expected) {
    if (strpos($html, $expected) === false) {
        fwrite(STDERR, 'Login do pacote sem conteúdo esperado: ' . $expected . PHP_EOL);
        exit(1);
    }
}
if (strpos($html, '/private/') !== false || strpos($html, '/public/') !== false) {
    fwrite(STDERR, 'Links do pacote apontam para diretório incorreto.' . PHP_EOL);
    exit(1);
}
if (!session_get_cookie_params()['secure'] || ini_get('display_errors')) {
    fwrite(STDERR, 'Configuração de produção incorreta.' . PHP_EOL);
    exit(1);
}
echo 'Login empacotado renderizou sem banco e com URLs corretas.' . PHP_EOL;
'@

$testFile = Join-Path $package.Directory 'verify-login.php'
[System.IO.File]::WriteAllText($testFile, "<?php`n" + $phpTest, [System.Text.UTF8Encoding]::new($false))
$phpOutput = & $php $testFile (Join-Path $package.Htdocs 'index.php') 2>&1
Assert-Package ($LASTEXITCODE -eq 0) ("Falha na execução do login empacotado: " + ($phpOutput -join [Environment]::NewLine))
Write-Host ($phpOutput -join [Environment]::NewLine)
Write-Host "$script:checks verificações do pacote aprovadas. Saída: $($package.Directory)"
