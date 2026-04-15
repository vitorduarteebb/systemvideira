# Script para criar ZIP com arquivos para upload
# Execute: .\criar_zip_upload.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  CRIANDO ZIP PARA UPLOAD" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Nome do arquivo ZIP
$zipName = "sistema_videira_upload.zip"
$zipPath = Join-Path $PSScriptRoot $zipName

# Remover ZIP anterior se existir
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
    Write-Host "✓ ZIP anterior removido" -ForegroundColor Yellow
}

Write-Host "Criando ZIP: $zipName" -ForegroundColor Green
Write-Host ""

# Arquivos e pastas para incluir
$itemsToInclude = @(
    ".htaccess",
    "artisan",
    "composer.json",
    "package.json",
    "vite.config.js",
    "phpunit.xml",
    "index.php",
    "app",
    "bootstrap",
    "config",
    "database",
    "resources",
    "routes",
    "storage"
)

# Criar ZIP
$zip = [System.IO.Compression.ZipFile]::Open($zipPath, [System.IO.Compression.ZipArchiveMode]::Create)

$addedCount = 0
$skippedCount = 0

foreach ($item in $itemsToInclude) {
    $itemPath = Join-Path $PSScriptRoot $item
    
    if (Test-Path $itemPath) {
        if (Test-Path $itemPath -PathType Container) {
            # É uma pasta - adicionar recursivamente
            $files = Get-ChildItem -Path $itemPath -Recurse -File
            foreach ($file in $files) {
                $relativePath = $file.FullName.Substring($PSScriptRoot.Length + 1)
                $relativePath = $relativePath.Replace('\', '/')
                
                # Excluir arquivos desnecessários
                if ($relativePath -match '(node_modules|vendor|\.git|\.env|\.DS_Store|Thumbs\.db|\.phpunit\.result\.cache)') {
                    $skippedCount++
                    continue
                }
                
                [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file.FullName, $relativePath) | Out-Null
                $addedCount++
            }
            Write-Host "✓ Pasta adicionada: $item" -ForegroundColor Green
        } else {
            # É um arquivo
            $relativePath = $item.Replace('\', '/')
            [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $itemPath, $relativePath) | Out-Null
            $addedCount++
            Write-Host "✓ Arquivo adicionado: $item" -ForegroundColor Green
        }
    } else {
        Write-Host "✗ Não encontrado: $item" -ForegroundColor Red
        $skippedCount++
    }
}

$zip.Dispose()

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ZIP CRIADO COM SUCESSO!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Arquivo: $zipPath" -ForegroundColor Yellow
Write-Host "Tamanho: $([math]::Round((Get-Item $zipPath).Length / 1MB, 2)) MB" -ForegroundColor Yellow
Write-Host ""
Write-Host "Arquivos adicionados: $addedCount" -ForegroundColor Green
Write-Host "Arquivos ignorados: $skippedCount" -ForegroundColor Gray
Write-Host ""
Write-Host "PRÓXIMOS PASSOS:" -ForegroundColor Cyan
Write-Host "1. Acesse o cPanel da sua hospedagem" -ForegroundColor White
Write-Host "2. Abra o File Manager" -ForegroundColor White
Write-Host "3. Vá para public_html/" -ForegroundColor White
Write-Host "4. Faça upload do arquivo: $zipName" -ForegroundColor White
Write-Host "5. Extraia o ZIP (botão direito > Extract)" -ForegroundColor White
Write-Host "6. Delete o arquivo ZIP após extrair" -ForegroundColor White
Write-Host ""
