# deploy-theme.ps1 - Automated theme build & SSH deployment to Hostinger

# Load environment variables from .env if present
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
$WpPath = if ($env:HOSTINGER_WP_PATH) { $env:HOSTINGER_WP_PATH } else { "domains/hwy559bridge.com/public_html" }

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host " Varner Theme - Hostinger SSH Deployment" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

$themeSource = "C:\Users\Greg\Desktop\Varner Equipent\varner-equipment-theme-lite\varner-lite"
if (-not (Test-Path $themeSource)) {
    Write-Host "[X] Theme source directory not found at: $themeSource" -ForegroundColor Red
    exit 1
}

Write-Host "[*] Packaging varner-lite theme..." -ForegroundColor Green
python tools/zip_helper.py varner-lite.zip $themeSource

if ($LASTEXITCODE -ne 0) {
    Write-Host "[X] Theme packaging failed." -ForegroundColor Red
    exit 1
}

$destPath = "$HostingerUser@${HostingerHost}:~/$WpPath/wp-content/themes/"
Write-Host "`n[*] Uploading varner-lite.zip to Hostinger ($destPath)..." -ForegroundColor Green
scp -P $HostingerPort varner-lite.zip $destPath

if ($LASTEXITCODE -ne 0) {
    Write-Host "[X] SCP theme upload failed." -ForegroundColor Red
    exit 1
}

Write-Host "[*] Extracting and activating varner-lite theme on Hostinger..." -ForegroundColor Green
$sshTarget = "$HostingerUser@$HostingerHost"
$fullWpPath = "/home/$HostingerUser/$WpPath"
$sshCmd = "cd $fullWpPath/wp-content/themes/ && rm -rf varner-lite && unzip -q varner-lite.zip && rm varner-lite.zip && wp theme activate varner-lite --path=$fullWpPath"

ssh -p $HostingerPort $sshTarget $sshCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "[+] Varner Equipment v23 Theme successfully deployed & activated on Hostinger!" -ForegroundColor Green
} else {
    Write-Host "[X] SSH theme activation failed." -ForegroundColor Red
}
