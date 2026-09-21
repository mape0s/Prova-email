$ErrorActionPreference = "Stop"

Write-Host "== Banco SPA: instalacao Svelte ==" -ForegroundColor Cyan

if (-not (Test-Path ".\artisan")) {
    throw "Execute este script na raiz do projeto Laravel (onde existe o arquivo artisan)."
}

$root = (Get-Location).Path
$patch = Split-Path -Parent $MyInvocation.MyCommand.Path

Write-Host "Copiando arquivos..." -ForegroundColor Yellow

$items = @(
    "package.json",
    "vite.config.js",
    "routes\api.php",
    "routes\web.php",
    "resources\views\svelte-app.blade.php",
    "resources\svelte",
    "app\Http\Controllers\Api\MovimentacaoController.php",
    "app\Services\MovimentacaoService.php"
)

foreach ($item in $items) {
    $source = Join-Path $patch $item
    $target = Join-Path $root $item

    if (Test-Path $source -PathType Container) {
        New-Item -ItemType Directory -Force -Path $target | Out-Null
        Copy-Item "$source\*" $target -Recurse -Force
    } else {
        New-Item -ItemType Directory -Force -Path (Split-Path $target) | Out-Null
        Copy-Item $source $target -Force
    }
}

Write-Host "Instalando dependencias NPM..." -ForegroundColor Yellow
npm install

Write-Host "Limpando caches do Laravel..." -ForegroundColor Yellow
php artisan optimize:clear

Write-Host "Gerando build Svelte/Vite..." -ForegroundColor Yellow
npm run build

Write-Host ""
Write-Host "OK. Agora execute:" -ForegroundColor Green
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Abra:" -ForegroundColor Green
Write-Host "  http://127.0.0.1:8000/" -ForegroundColor White
