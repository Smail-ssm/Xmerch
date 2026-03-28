param(
    [string]$ProjectRoot = "C:\laragon\www\xmerch\project",
    [string]$OutputDir = "",
    [switch]$ZipOutput
)

$ErrorActionPreference = "Stop"

function Get-EnvValue {
    param(
        [string]$EnvFile,
        [string]$Key
    )

    $line = Get-Content -LiteralPath $EnvFile |
        Where-Object { $_ -match "^\s*$Key\s*=" } |
        Select-Object -First 1

    if (-not $line) {
        return $null
    }

    $value = ($line -split "=", 2)[1]
    return $value.Trim('"').Trim("'")
}

if (-not $OutputDir) {
    $OutputDir = Join-Path $ProjectRoot "storage\backups"
}

$envFile = Join-Path $ProjectRoot ".env"
if (-not (Test-Path -LiteralPath $envFile)) {
    throw "Missing .env file at $envFile"
}

$dbHost = Get-EnvValue -EnvFile $envFile -Key "DB_HOST"
$dbPort = Get-EnvValue -EnvFile $envFile -Key "DB_PORT"
$dbName = Get-EnvValue -EnvFile $envFile -Key "DB_DATABASE"
$dbUser = Get-EnvValue -EnvFile $envFile -Key "DB_USERNAME"
$dbPass = Get-EnvValue -EnvFile $envFile -Key "DB_PASSWORD"

if (-not $dbHost) { $dbHost = "127.0.0.1" }
if (-not $dbPort) { $dbPort = "3306" }

$mysqldump = (Get-Command mysqldump -ErrorAction SilentlyContinue).Source
if (-not $mysqldump) {
    $laragonDump = "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe"
    if (Test-Path -LiteralPath $laragonDump) {
        $mysqldump = $laragonDump
    }
}

if (-not $mysqldump) {
    throw "mysqldump not found in PATH and not found in Laragon default path."
}

New-Item -ItemType Directory -Path $OutputDir -Force | Out-Null

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$sqlFile = Join-Path $OutputDir ("xmerch_local_{0}.sql" -f $timestamp)

$env:MYSQL_PWD = $dbPass
& $mysqldump `
    --host=$dbHost `
    --port=$dbPort `
    --user=$dbUser `
    --default-character-set=utf8mb4 `
    --single-transaction `
    --routines `
    --triggers `
    --events `
    --column-statistics=0 `
    $dbName > $sqlFile

if ($LASTEXITCODE -ne 0) {
    throw "mysqldump failed with exit code $LASTEXITCODE"
}

Remove-Item Env:MYSQL_PWD -ErrorAction SilentlyContinue

$outputFile = $sqlFile
if ($ZipOutput) {
    $zipFile = [System.IO.Path]::ChangeExtension($sqlFile, "zip")
    if (Test-Path -LiteralPath $zipFile) {
        Remove-Item -LiteralPath $zipFile -Force
    }
    Compress-Archive -Path $sqlFile -DestinationPath $zipFile
    $outputFile = $zipFile
}

$sha256 = (Get-FileHash -Algorithm SHA256 -Path $outputFile).Hash
$hashFile = "$outputFile.sha256"
Set-Content -LiteralPath $hashFile -Value "$sha256 *$([System.IO.Path]::GetFileName($outputFile))" -Encoding ascii

Write-Host "Backup created:"
Write-Host "  SQL:  $sqlFile"
if ($ZipOutput) {
    Write-Host "  ZIP:  $outputFile"
}
Write-Host "  HASH: $sha256"
Write-Host "  SHA file: $hashFile"

