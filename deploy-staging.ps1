# deploy-staging.ps1 - Automated Staging Deployment to dev.hwy559bridge.com

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
$StagingPath = "domains/hwy559bridge.com/public_html/dev"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host " Staging Deployment -> dev.hwy559bridge.com" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Build & Deploy Plugin
Write-Host "[1/2] Building and deploying Bridge OS Plugin..." -ForegroundColor Green
& ./build.ps1

$pluginDest = "$HostingerUser@${HostingerHost}:~/$StagingPath/wp-content/plugins/"
scp -P $HostingerPort bridge-plugin.zip $pluginDest

# 2. Build & Deploy Theme
Write-Host "`n[2/2] Packaging and deploying HWY 559 Bridge Theme..." -ForegroundColor Green
python tools/zip_helper.py hwy559bridge-theme.zip "c:\Users\Greg\Desktop\hwy559bridge_v1.0.0\hwy559bridge-theme"
$themeDest = "$HostingerUser@${HostingerHost}:~/$StagingPath/wp-content/themes/"
scp -P $HostingerPort hwy559bridge-theme.zip $themeDest

# 3. Activate Theme, Plugin & Seed Staging DB
Write-Host "`n[*] Activating Theme, Plugin & Seeding Staging DB via SSH..." -ForegroundColor Green
$fullStagingPath = "/home/$HostingerUser/$StagingPath"
$sshCmd = "cd $fullStagingPath/wp-content/plugins/ && rm -rf bridge-plugin && unzip -q bridge-plugin.zip && rm bridge-plugin.zip && cd $fullStagingPath/wp-content/themes/ && rm -rf hwy559bridge-theme && unzip -q hwy559bridge-theme.zip && rm hwy559bridge-theme.zip && wp plugin activate bridge-plugin --path=$fullStagingPath && wp theme activate hwy559bridge-theme --path=$fullStagingPath && wp bridge seed --fresh --path=$fullStagingPath"

ssh -p $HostingerPort "$HostingerUser@$HostingerHost" $sshCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n[+] Staging Deployment Complete! Live at https://dev.hwy559bridge.com" -ForegroundColor Green
} else {
    Write-Host "[X] SSH staging deployment failed." -ForegroundColor Red
}
