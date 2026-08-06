# deploy.ps1 - Automated SSH deployment to Hostinger

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
Write-Host " Bridge OS - Hostinger Automated Deployment" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

if (-not $HostingerHost -or -not $HostingerUser) {
    Write-Host "[!] Hostinger SSH connection details required." -ForegroundColor Yellow
    exit 1
}

if (-not (Test-Path "bridge-plugin.zip")) {
    Write-Host "[*] bridge-plugin.zip not found. Building plugin package first..." -ForegroundColor Yellow
    & ./build.ps1
}

$destPath = "$HostingerUser@${HostingerHost}:~/$WpPath/wp-content/plugins/"
Write-Host "`n[*] Uploading bridge-plugin.zip to Hostinger ($destPath)..." -ForegroundColor Green
scp -P $HostingerPort bridge-plugin.zip $destPath

if ($LASTEXITCODE -ne 0) {
    Write-Host "[X] SCP upload failed. Please verify your SSH key is added in Hostinger hPanel." -ForegroundColor Red
    exit 1
}

Write-Host "[*] Extracting plugin package and activating WordPress plugin..." -ForegroundColor Green
$sshTarget = "$HostingerUser@$HostingerHost"
$fullWpPath = "/home/$HostingerUser/$WpPath"
$sshCmd = "cd $fullWpPath/wp-content/plugins/ && rm -rf bridge-plugin && unzip -q bridge-plugin.zip && rm bridge-plugin.zip && wp plugin activate bridge-plugin --path=$fullWpPath && wp bridge seed --fresh --path=$fullWpPath"

ssh -p $HostingerPort $sshTarget $sshCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "[+] Deployment complete! Bridge plugin v1.0.6 successfully deployed & activated on Hostinger." -ForegroundColor Green
} else {
    Write-Host "[X] SSH command execution failed." -ForegroundColor Red
}
