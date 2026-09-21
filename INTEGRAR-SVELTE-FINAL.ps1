$ErrorActionPreference = 'Stop'

if (-not (Test-Path '.\artisan')) {
    Write-Host 'ERRO: execute este script na raiz do projeto Laravel (onde esta o artisan).' -ForegroundColor Red
    exit 1
}

$stamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$backup = ".\backup-svelte-$stamp"
New-Item -ItemType Directory -Path $backup -Force | Out-Null

$files = @(
    'resources\js\app.js',
    'resources\js\App.svelte',
    'resources\css\app.css',
    'resources\views\spa.blade.php',
    'vite.config.js'
)

foreach ($file in $files) {
    if (Test-Path ".\$file") {
        $dest = Join-Path $backup $file
        New-Item -ItemType Directory -Path (Split-Path $dest) -Force | Out-Null
        Copy-Item ".\$file" $dest -Force
    }
}

$root = Split-Path -Parent $MyInvocation.MyCommand.Path
Copy-Item "$root\resources\js\app.js" '.\resources\js\app.js' -Force
Copy-Item "$root\resources\js\App.svelte" '.\resources\js\App.svelte' -Force
Copy-Item "$root\resources\css\app.css" '.\resources\css\app.css' -Force
Copy-Item "$root\resources\views\spa.blade.php" '.\resources\views\spa.blade.php' -Force
Copy-Item "$root\vite.config.js" '.\vite.config.js' -Force

docker compose exec app npm install svelte @sveltejs/vite-plugin-svelte --save-dev
docker compose exec app php artisan optimize:clear
docker compose exec app npm run build

Write-Host ''
Write-Host 'Svelte antigo substituido pela nova SPA Svelte.' -ForegroundColor Green
Write-Host "Backup criado em: $backup" -ForegroundColor Cyan
Write-Host 'Acesse: http://localhost/spa' -ForegroundColor Cyan
