# deploy-theme.ps1 - Automated HWY 559 Bridge Theme deployment to Hostinger

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
Write-Host " HWY 559 Bridge Theme - Hostinger Deployment" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

$themeSource = "c:\Users\Greg\Desktop\hwy559bridge_v1.0.0\hwy559bridge-theme"
if (-not (Test-Path $themeSource)) {
    Write-Host "[X] Theme directory not found at: $themeSource" -ForegroundColor Red
    exit 1
}

Write-Host "[*] Packaging HWY 559 Bridge Theme..." -ForegroundColor Green
python tools/zip_helper.py hwy559bridge-theme.zip $themeSource

if ($LASTEXITCODE -ne 0) {
    Write-Host "[X] Theme packaging failed." -ForegroundColor Red
    exit 1
}

$destPath = "$HostingerUser@${HostingerHost}:~/$WpPath/wp-content/themes/"
Write-Host "`n[*] Uploading hwy559bridge-theme.zip to Hostinger ($destPath)..." -ForegroundColor Green
scp -P $HostingerPort hwy559bridge-theme.zip $destPath

if ($LASTEXITCODE -ne 0) {
    Write-Host "[X] SCP theme upload failed." -ForegroundColor Red
    exit 1
}

Write-Host "[*] Extracting and activating HWY 559 Bridge Theme on Hostinger..." -ForegroundColor Green
$sshTarget = "$HostingerUser@$HostingerHost"
$fullWpPath = "/home/$HostingerUser/$WpPath"
$sshCmd = "cd $fullWpPath/wp-content/themes/ && rm -rf hwy559bridge-theme theme && unzip -q hwy559bridge-theme.zip && rm hwy559bridge-theme.zip && wp theme activate hwy559bridge-theme --path=$fullWpPath"

ssh -p $HostingerPort $sshTarget $sshCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "[+] HWY 559 Bridge Theme successfully deployed & activated on Hostinger!" -ForegroundColor Green
} else {
    Write-Host "[X] SSH theme activation failed." -ForegroundColor Red
}
