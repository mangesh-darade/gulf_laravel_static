# Build static deploy (HTML + CSS + JS + JSON only — no PHP)
$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $PSScriptRoot
$source = Join-Path $root "public"
$prodDir = Join-Path $source "mobility-and-recovery"
$outDir = Split-Path -Parent $root

$deployFiles = @("index.html", "manage.html", "robots.txt")
$deployCss = @("gulf-landing.css", "manage.css")
$deployJs = @("gulf-landing.js", "manage.js", "mobility-config.js")

if (Test-Path $prodDir) { Remove-Item $prodDir -Recurse -Force }
New-Item -ItemType Directory -Path "$prodDir\css", "$prodDir\js", "$prodDir\data", "$prodDir\images" | Out-Null

foreach ($f in $deployFiles) { Copy-Item "$source\$f" $prodDir }
foreach ($f in $deployCss) { Copy-Item "$source\css\$f" "$prodDir\css\" }
foreach ($f in $deployJs) { Copy-Item "$source\js\$f" "$prodDir\js\" }
Copy-Item "$source\data\*.json" "$prodDir\data\"
Get-ChildItem "$source\images" -File | Where-Object { $_.Name -ne '.DS_Store' } | Copy-Item -Destination "$prodDir\images\"
Copy-Item "$source\.htaccess.production" "$prodDir\.htaccess"

$readme = @"
STATIC DEPLOY - HTML, CSS, JS, JSON only (no PHP)

Upload mobility-and-recovery/ folder to your server.

URLs:
  Landing: /mobility-and-recovery/
  Admin:   /mobility-and-recovery/manage.html
  Password: gulf2026

After editing in admin:
  1. Click Download products.json
  2. Upload to data/products.json on server (Plesk)

Leads: stored in browser until you Download leads.json and upload to data/
"@

# DEV zip
$devTemp = Join-Path $env:TEMP "gulf_dev_$(Get-Random)"
New-Item -ItemType Directory -Path $devTemp | Out-Null
foreach ($f in $deployFiles) { Copy-Item "$source\$f" $devTemp }
New-Item -ItemType Directory -Path "$devTemp\css", "$devTemp\js", "$devTemp\data", "$devTemp\images" | Out-Null
foreach ($f in $deployCss) { Copy-Item "$source\css\$f" "$devTemp\css\" }
foreach ($f in $deployJs) { Copy-Item "$source\js\$f" "$devTemp\js\" }
Copy-Item "$source\data\*.json" "$devTemp\data\"
Get-ChildItem "$source\images" -File | Where-Object { $_.Name -ne '.DS_Store' } | Copy-Item -Destination "$devTemp\images\"
Copy-Item "$source\.htaccess.deploy" "$devTemp\.htaccess"
Set-Content "$devTemp\DEPLOY_README.txt" $readme -Encoding UTF8
$devZip = Join-Path $outDir "gulf_landing_static_deploy_DEV.zip"
if (Test-Path $devZip) { Remove-Item $devZip -Force }
Compress-Archive -Path "$devTemp\*" -DestinationPath $devZip -CompressionLevel Optimal
Remove-Item $devTemp -Recurse -Force

# PRODUCTION zip
$prodTemp = Join-Path $env:TEMP "gulf_prod_$(Get-Random)"
New-Item -ItemType Directory -Path "$prodTemp\mobility-and-recovery" | Out-Null
Copy-Item "$prodDir\*" "$prodTemp\mobility-and-recovery\" -Recurse -Force
Set-Content "$prodTemp\DEPLOY_README.txt" $readme -Encoding UTF8
$prodZip = Join-Path $outDir "gulf_landing_static_deploy_PRODUCTION.zip"
if (Test-Path $prodZip) { Remove-Item $prodZip -Force }
Compress-Archive -Path "$prodTemp\*" -DestinationPath $prodZip -CompressionLevel Optimal
Remove-Item $prodTemp -Recurse -Force

Write-Host "STATIC deploy - no PHP"
Write-Host "DEV: $devZip"
Write-Host "PRODUCTION: $prodZip"
