# deploy-coming-soon.ps1 - Deploys "Coming Home Soon" page to main hwy559bridge.com

if (Test-Path ".env") {
    Get-Content ".env" | ForEach-Object {
        $line = $_.Trim()
        if ($line -and -not $line.StartsWith("#") -and $line.Contains("=")) {
            $key, $val = $line.Split("=", 2)
            [System.Environment]::SetEnvironmentVariable($key.Trim(), $val.Trim(), [System.EnvironmentVariableTarget]::Process)
        }
    }
}

$HostingerHost = $env:HOSTINGER_HOST
$HostingerUser = $env:HOSTINGER_USER
$HostingerPort = if ($env:HOSTINGER_PORT) { [int]$env:HOSTINGER_PORT } else { 65002 }
$MainPath = "domains/hwy559bridge.com/public_html"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host " HWY 559 Bridge - Deploying Coming Soon Page" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

if (-not (Test-Path "coming-soon/index.html")) {
    Write-Host "[X] coming-soon/index.html not found!" -ForegroundColor Red
    exit 1
}

$destPath = "$HostingerUser@${HostingerHost}:~/$MainPath/index.html"
Write-Host "[*] Uploading Coming Soon page to hwy559bridge.com ($destPath)..." -ForegroundColor Green
scp -P $HostingerPort coming-soon/index.html $destPath

if ($LASTEXITCODE -eq 0) {
    Write-Host "[+] 'Coming Home Soon' page live on https://hwy559bridge.com!" -ForegroundColor Green
} else {
    Write-Host "[X] Upload failed." -ForegroundColor Red
}
