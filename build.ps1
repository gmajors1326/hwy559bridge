# Bridge Plugin - Build & Packaging Script
# Compiles React (Vite writes directly to bridge-plugin/dist/),
# auto-bumps plugin version, and packages bridge-plugin.zip.

$ErrorActionPreference = 'Stop'

$root = Get-Location

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Starting Bridge Plugin Build..."           -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Compile React → bridge-plugin/dist/ (outDir set in vite.config.js)
Write-Host "`n[1/2] Building React Application..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "React build failed! Aborting." -ForegroundColor Red
    exit $LASTEXITCODE
}
Write-Host "  Vite output -> bridge-plugin\dist\" -ForegroundColor Green

# 2. Auto-Increment Plugin Version (busts CDN/WP cache, forces overwrite prompt)
Write-Host "`n[2/2] Auto-incrementing plugin version & packaging..." -ForegroundColor Yellow
$pluginFolder = "bridge-plugin"
$pluginFile   = "$pluginFolder\bridge-plugin.php"

if (Test-Path $pluginFile) {
    $pluginContent = Get-Content $pluginFile -Raw
    if ($pluginContent -match 'Version:\s*(\d+)\.(\d+)\.(\d+)') {
        $major   = $Matches[1]
        $minor   = $Matches[2]
        $patch   = [int]$Matches[3] + 1
        $newVer  = "$major.$minor.$patch"

        $pluginContent = $pluginContent -replace 'Version:\s*\d+\.\d+\.\d+', "Version: $newVer"
        $pluginContent = $pluginContent -replace 'Version \d+\.\d+\.\d+ - React-powered', "Version $newVer - React-powered"
        Set-Content $pluginFile $pluginContent -NoNewline
        Write-Host "  Plugin version bumped to: $newVer" -ForegroundColor Green

        # Sync to readme.txt
        $pluginReadme = "$pluginFolder\readme.txt"
        if (Test-Path $pluginReadme) {
            $readmeContent = Get-Content $pluginReadme -Raw
            if ($readmeContent -match 'Version:\s*\d+\.\d+\.\d+') {
                $readmeContent = $readmeContent -replace 'Version:\s*\d+\.\d+\.\d+', "Version: $newVer"
                Set-Content $pluginReadme $readmeContent -NoNewline
                Write-Host "  readme.txt version bumped to: $newVer" -ForegroundColor Green
            } else {
                Write-Host "Warning: could not match version in readme.txt" -ForegroundColor Yellow
            }
        }
    } else {
        Write-Host "Warning: could not match version regex in plugin header" -ForegroundColor Red
    }
} else {
    Write-Host "Error: $pluginFile not found" -ForegroundColor Red
}

# Package Plugin ZIP
$pluginZipName = "bridge-plugin.zip"
$pluginZip     = Join-Path $root $pluginZipName
if (Test-Path $pluginZip) { Remove-Item $pluginZip -Force }
python tools/zip_helper.py $pluginZip "bridge-plugin"
Write-Host "  Plugin packaged -> $pluginZip" -ForegroundColor Green

Write-Host "`n==========================================" -ForegroundColor Green
Write-Host "Bridge Build Complete!"                      -ForegroundColor Green
Write-Host "  Plugin ZIP: $pluginZipName"               -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green