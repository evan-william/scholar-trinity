param(
    [string] $ArchivePath = "scholar-trinity-deploy.zip"
)

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
$archiveFullPath = [System.IO.Path]::GetFullPath((Join-Path $projectRoot $ArchivePath))
$previousLocation = Get-Location

$items = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "docs",
    "lang",
    "public",
    "resources",
    "routes",
    "scripts",
    "tests",
    "storage/app/.gitignore",
    "storage/app/private/.gitignore",
    "storage/app/public/.gitignore",
    "storage/framework/.gitignore",
    "storage/framework/cache/.gitignore",
    "storage/framework/cache/data/.gitignore",
    "storage/framework/sessions/.gitignore",
    "storage/framework/testing/.gitignore",
    "storage/framework/views/.gitignore",
    "storage/logs/.gitignore",
    ".editorconfig",
    ".env.example",
    ".env.production.example",
    ".gitattributes",
    ".gitignore",
    "artisan",
    "composer.json",
    "composer.lock",
    "package.json",
    "package-lock.json",
    "phpunit.xml",
    "vite.config.js",
    "README.md",
    "LICENSE",
    "PROGRESS.md",
    "DEPLOY_COMMANDS.md",
    "DEPLOYMENT.md",
    "EDITOR_NOTE.md",
    "GITHUB_REPO.md",
    "INTEGRATIONS.md",
    "SERVER_CHECKLIST.md",
    "SERVER_UPLOAD_GUIDE.md"
)

$excludeArguments = @(
    "--exclude=database/*.sqlite",
    "--exclude=database/*.sqlite-*",
    "--exclude=bootstrap/cache/*.php",
    "--exclude=storage/logs/*",
    "--exclude=storage/app/private/*",
    "--exclude=storage/app/student-passports/*",
    "--exclude=storage/app/payment-proofs/*"
)

try {
    Set-Location $projectRoot
    & tar.exe -a -c -f $archiveFullPath @excludeArguments @items

    if ($LASTEXITCODE -ne 0) {
        throw "tar.exe failed with exit code $LASTEXITCODE."
    }

    $entries = @(& tar.exe -tf $archiveFullPath)
    $forbiddenPatterns = @(
        "^\.env$",
        "^database/.*\.sqlite(?:-.+)?$",
        "^vendor/",
        "^node_modules/",
        "^storage/logs/(?!\.gitignore$).+",
        "^storage/app/private/(?!\.gitignore$).+",
        "^storage/app/student-passports/",
        "^storage/app/payment-proofs/",
        "^\.git/"
    )

    foreach ($pattern in $forbiddenPatterns) {
        $match = $entries | Where-Object { $_ -match $pattern } | Select-Object -First 1
        if ($match) {
            throw "Unsafe archive entry detected: $match"
        }
    }

    $requiredEntries = @(
        "artisan",
        "composer.json",
        "database/migrations/",
        "public/build/manifest.json",
        "app/Console/Commands/SyncRegistrationCatalog.php"
    )

    foreach ($entry in $requiredEntries) {
        if (-not ($entries | Where-Object { $_ -like "$entry*" } | Select-Object -First 1)) {
            throw "Required archive entry is missing: $entry"
        }
    }

    $archive = Get-Item -LiteralPath $archiveFullPath
    $hash = (Get-FileHash -LiteralPath $archiveFullPath -Algorithm SHA256).Hash
    Write-Output "Deploy ZIP ready: $($archive.FullName)"
    Write-Output "Entries: $($entries.Count)"
    Write-Output "Bytes: $($archive.Length)"
    Write-Output "SHA256: $hash"
} finally {
    Set-Location $previousLocation
}
