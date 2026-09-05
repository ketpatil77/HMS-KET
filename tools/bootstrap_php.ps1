param(
    [Parameter(Mandatory = $true)]
    [string]$RootDir,
    [switch]$ForceProjectRuntime
)

$ErrorActionPreference = 'Stop'
try {
    Import-Module Microsoft.PowerShell.Utility -ErrorAction SilentlyContinue
} catch {
}
try {
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
} catch {
}

function Write-Step {
    param([string]$Message)
    [Console]::Error.WriteLine($Message)
}

function Combine-Path2 {
    param(
        [string]$Left,
        [string]$Right
    )

    return [System.IO.Path]::Combine($Left, $Right)
}

function Path-Exists {
    param([string]$PathValue)
    return [System.IO.File]::Exists($PathValue) -or [System.IO.Directory]::Exists($PathValue)
}

function Ensure-Directory {
    param([string]$PathValue)
    if (-not [System.IO.Directory]::Exists($PathValue)) {
        [System.IO.Directory]::CreateDirectory($PathValue) | Out-Null
    }
}

function Quote-ProcessArgument {
    param([string]$Value)
    if ($null -eq $Value) { return '""' }
    return '"' + ($Value -replace '\\', '\\' -replace '"', '\"') + '"'
}

function Read-AllText {
    param([string]$PathValue)
    return [System.IO.File]::ReadAllText($PathValue)
}

function Write-AllTextAscii {
    param(
        [string]$PathValue,
        [string]$Content
    )
    $encoding = [System.Text.ASCIIEncoding]::new()
    [System.IO.File]::WriteAllText($PathValue, $Content, $encoding)
}

function Download-File {
    param(
        [string]$Url,
        [string]$OutFile
    )

    $client = [System.Net.WebClient]::new()
    $client.Headers['User-Agent'] = 'Hospital-Management-System-Bootstrap'
    $client.DownloadFile($Url, $OutFile)
}

function Download-String {
    param([string]$Url)

    $client = [System.Net.WebClient]::new()
    $client.Headers['User-Agent'] = 'Hospital-Management-System-Bootstrap'
    return $client.DownloadString($Url)
}

function Expand-ZipFile {
    param(
        [string]$ZipPath,
        [string]$Destination
    )

    try {
        Add-Type -AssemblyName System.IO.Compression.FileSystem -ErrorAction Stop
        [System.IO.Compression.ZipFile]::ExtractToDirectory($ZipPath, $Destination)
        return
    } catch {
    }

    $shell = New-Object -ComObject Shell.Application
    $zip = $shell.NameSpace($ZipPath)
    $dest = $shell.NameSpace($Destination)
    if ($zip -eq $null -or $dest -eq $null) {
        throw 'Could not open zip archive for extraction.'
    }
    $dest.CopyHere($zip.Items(), 16)
}

function Stop-ProjectPhpProcesses {
    param([string]$PhpFolder)

    $prefix = [System.IO.Path]::GetFullPath($PhpFolder).TrimEnd('\') + '\'
    foreach ($process in [System.Diagnostics.Process]::GetProcessesByName('php')) {
        try {
            $path = $process.MainModule.FileName
            if ($path -and $path.StartsWith($prefix, [System.StringComparison]::OrdinalIgnoreCase)) {
                Write-Step ("Stopping old project PHP process: {0}" -f $process.Id)
                $process.Kill()
                $process.WaitForExit(5000)
            }
        } catch {
        }
    }
}

function Remove-DirectorySafe {
    param([string]$PathValue)

    if (-not [System.IO.Directory]::Exists($PathValue)) {
        return
    }
    try {
        [System.IO.Directory]::Delete($PathValue, $true)
    } catch {
        throw ("Could not replace existing PHP runtime. Close old app windows and run START.bat again. Detail: {0}" -f $_.Exception.Message)
    }
}

function Get-Requirements {
    param([string]$RequirementsPath)

    $settings = @{
        APP_NAME = 'Hospital Management System'
        DATA_MODE = 'json'
        PHP_VERSION_PREFIX = '8.3'
        PHP_ARCH = 'x64'
        PHP_BUILD = 'nts'
        PHP_TOOLCHAIN = 'vs16'
        PHP_LATEST_PAGE = 'https://downloads.php.net/~windows/releases/latest/'
        PHP_RELEASE_PAGE = 'https://windows.php.net/downloads/releases/'
        PHP_ARCHIVE_PAGE = 'https://windows.php.net/downloads/releases/archives/'
        PHP_ENABLE_EXTENSIONS = 'curl,fileinfo,gd,mbstring,openssl'
        PHP_REQUIRED_EXTENSIONS = 'json,curl,fileinfo,gd,mbstring,openssl'
        VC_REDIST_URL = 'https://aka.ms/vs/17/release/vc_redist.x64.exe'
    }

    if ([System.IO.File]::Exists($RequirementsPath)) {
        foreach ($line in [System.IO.File]::ReadAllLines($RequirementsPath)) {
            $trimmed = $line.Trim()
            if (-not $trimmed) { continue }
            if ($trimmed.StartsWith('#')) { continue }
            $parts = $trimmed -split '=', 2
            if ($parts.Count -ne 2) { continue }
            $key = $parts[0].Trim()
            $value = $parts[1].Trim()
            if ($key) {
                $settings[$key] = $value
            }
        }
    }

    return $settings
}

function Get-EnabledExtensions {
    param($Requirements)

    $raw = $Requirements['PHP_ENABLE_EXTENSIONS']
    if (-not $raw) { return @() }
    return @($raw -split ',' | ForEach-Object { $_.Trim() } | Where-Object { $_ })
}

function Get-Latest-PhpZipUrl {
    param($Requirements)

    $latestPage = $Requirements['PHP_LATEST_PAGE']
    $versionPrefix = $Requirements['PHP_VERSION_PREFIX']
    $arch = $Requirements['PHP_ARCH']
    $toolchain = $Requirements['PHP_TOOLCHAIN']
    $buildValue = $Requirements['PHP_BUILD']
    if ([string]::IsNullOrWhiteSpace($buildValue)) {
        $buildValue = 'nts'
    }
    $build = $buildValue.ToLowerInvariant()

    if (-not [string]::IsNullOrWhiteSpace($latestPage) -and
        -not [string]::IsNullOrWhiteSpace($versionPrefix) -and
        -not [string]::IsNullOrWhiteSpace($arch) -and
        -not [string]::IsNullOrWhiteSpace($toolchain)) {
        if ($build -eq 'nts') {
            return ($latestPage.TrimEnd('/') + '/php-' + $versionPrefix + '-nts-Win32-' + $toolchain + '-' + $arch + '-latest.zip')
        } else {
            return ($latestPage.TrimEnd('/') + '/php-' + $versionPrefix + '-Win32-' + $toolchain + '-' + $arch + '-latest.zip')
        }
    }

    $pages = @(
        $Requirements['PHP_LATEST_PAGE'],
        $Requirements['PHP_RELEASE_PAGE'],
        $Requirements['PHP_ARCHIVE_PAGE']
    )

    $versionPrefix = [regex]::Escape($Requirements['PHP_VERSION_PREFIX'])
    $arch = [regex]::Escape($Requirements['PHP_ARCH'])
    $buildValue = $Requirements['PHP_BUILD']
    if ([string]::IsNullOrWhiteSpace($buildValue)) {
        $buildValue = 'nts'
    }
    $build = $buildValue.ToLowerInvariant()

    if ($build -eq 'nts') {
        $latestPattern = "/php-$versionPrefix-nts-Win32-vs\d+-$arch-latest\.zip"
        $pattern = "/downloads/releases(?:/archives)?/php-$versionPrefix\.[0-9]+-nts-Win32-vs\d+-$arch\.zip"
    } else {
        $latestPattern = "/php-$versionPrefix-Win32-vs\d+-$arch-latest\.zip"
        $pattern = "/downloads/releases(?:/archives)?/php-$versionPrefix\.[0-9]+-Win32-vs\d+-$arch\.zip"
    }

    $latestFallbackPattern = "/php-[0-9.]+(?:-nts)?-Win32-vs\d+-$arch-latest\.zip"
    $fallbackPattern = "/downloads/releases(?:/archives)?/php-[0-9.]+(?:-nts)?-Win32-vs\d+-$arch\.zip"

    foreach ($pageUrl in $pages) {
        try {
            Write-Step "Checking PHP releases: $pageUrl"
            $content = Download-String -Url $pageUrl
            $matches = @()
            if ($pageUrl -like '*latest*') {
                $matches = [regex]::Matches($content, $latestPattern)
                if ($matches.Count -eq 0) {
                    $matches = [regex]::Matches($content, $latestFallbackPattern)
                }
                if ($matches.Count -gt 0) {
                    return ($pageUrl.TrimEnd('/') + '/' + $matches[0].Value.TrimStart('/'))
                }
            } else {
                $matches = [regex]::Matches($content, $pattern)
                if ($matches.Count -eq 0) {
                    $matches = [regex]::Matches($content, $fallbackPattern)
                }
                if ($matches.Count -gt 0) {
                    return 'https://windows.php.net' + $matches[0].Value
                }
            }
        } catch {
            Write-Step "Release page failed: $pageUrl"
        }
    }

    throw 'Could not find PHP Windows zip URL from requirements.txt config.'
}

function Enable-PhpExtensions {
    param(
        [string]$PhpFolder,
        [string[]]$Extensions
    )

    $ini = Combine-Path2 $PhpFolder 'php.ini'
    if (-not [System.IO.File]::Exists($ini)) {
        $template = Combine-Path2 $PhpFolder 'php.ini-development'
        if (-not [System.IO.File]::Exists($template)) {
            throw 'php.ini-development not found after PHP download.'
        }
        [System.IO.File]::Copy($template, $ini, $true)
    }

    $content = Read-AllText $ini
    $content = [regex]::Replace($content, '(?m)^;?\s*extension_dir\s*=.*$', 'extension_dir = "ext"')
    if ($content -notmatch '(?m)^extension_dir\s*=') {
        $content += "`r`nextension_dir = `"ext`"`r`n"
    }

    foreach ($extension in $Extensions) {
        if (-not $extension) { continue }
        $escaped = [regex]::Escape($extension)
        $content = [regex]::Replace($content, "(?m)^;?\s*extension\s*=\s*$escaped\s*$", "extension=$extension")
        if ($content -notmatch "(?m)^extension\s*=\s*$escaped\s*$") {
            $content += "`r`nextension=$extension`r`n"
        }
    }

    if ($content -notmatch '(?m)^date\.timezone\s*=') {
        $content += "`r`ndate.timezone = UTC`r`n"
    } else {
        $content = [regex]::Replace($content, '(?m)^;?\s*date\.timezone\s*=.*$', 'date.timezone = UTC')
    }

    Write-AllTextAscii -PathValue $ini -Content $content
}

function Install-VcRedist {
    param(
        $Requirements,
        [string]$VcRedistPath
    )

    Write-Step 'Trying VC++ runtime install.'
    $url = $Requirements['VC_REDIST_URL']
    Download-File -Url $url -OutFile $VcRedistPath
    $info = [System.Diagnostics.ProcessStartInfo]::new()
    $info.FileName = $VcRedistPath
    $info.Arguments = '/install /quiet /norestart'
    $info.UseShellExecute = $false
    $process = [System.Diagnostics.Process]::Start($info)
    $process.WaitForExit()
    return $process.ExitCode
}

function Ensure-PhpDownload {
    param(
        $Requirements,
        [string]$RuntimeDir,
        [string]$PhpDir,
        [string]$DownloadZip
    )

    Ensure-Directory $RuntimeDir

    $url = Get-Latest-PhpZipUrl -Requirements $Requirements
    Write-Step "Downloading PHP: $url"
    Download-File -Url $url -OutFile $DownloadZip

    Stop-ProjectPhpProcesses -PhpFolder $PhpDir
    Remove-DirectorySafe -PathValue $PhpDir
    Ensure-Directory $PhpDir

    Expand-ZipFile -ZipPath $DownloadZip -Destination $PhpDir
    if ([System.IO.File]::Exists($DownloadZip)) {
        [System.IO.File]::Delete($DownloadZip)
    }

    Enable-PhpExtensions -PhpFolder $PhpDir -Extensions (Get-EnabledExtensions -Requirements $Requirements)
}

function Test-Php {
    param([string]$ExePath)

    $info = [System.Diagnostics.ProcessStartInfo]::new()
    $info.FileName = $ExePath
    $info.Arguments = '-v'
    $info.UseShellExecute = $false
    $info.RedirectStandardOutput = $true
    $info.RedirectStandardError = $true
    $process = [System.Diagnostics.Process]::Start($info)
    $process.WaitForExit()
    return $process.ExitCode -eq 0
}

function Test-PhpRequiredExtensions {
    param(
        [string]$ExePath,
        [string]$RequiredExtensions
    )

    $raw = $RequiredExtensions
    if ([string]::IsNullOrWhiteSpace($raw)) {
        $raw = 'json,curl,fileinfo,gd,mbstring,openssl'
    }
    $code = '$required = array_filter(array_map("trim", explode(",", getenv("HMS_REQUIRED_EXTENSIONS")))); $missing = array(); foreach ($required as $ext) { if (!extension_loaded($ext)) { $missing[] = $ext; } } if ($missing) { fwrite(STDERR, implode(",", $missing)); exit(2); }'
    $info = [System.Diagnostics.ProcessStartInfo]::new()
    $info.FileName = $ExePath
    $info.Arguments = '-r ' + (Quote-ProcessArgument $code)
    $info.UseShellExecute = $false
    $info.RedirectStandardOutput = $true
    $info.RedirectStandardError = $true
    $info.EnvironmentVariables['HMS_REQUIRED_EXTENSIONS'] = $raw
    $process = [System.Diagnostics.Process]::Start($info)
    $process.WaitForExit()
    return $process.ExitCode -eq 0
}

function Find-SystemPhp {
    $command = Get-Command php.exe -ErrorAction SilentlyContinue
    if ($command -and $command.Source) {
        return $command.Source
    }
    return $null
}

$runtimeDir = Combine-Path2 $RootDir '.runtime'
$phpDir = Combine-Path2 $runtimeDir 'php'
$phpExe = Combine-Path2 $phpDir 'php.exe'
$downloadZip = Combine-Path2 $runtimeDir 'php.zip'
$vcRedist = Combine-Path2 $runtimeDir 'vc_redist.x64.exe'
$requirementsPath = Combine-Path2 $RootDir 'requirements.txt'

Ensure-Directory $runtimeDir
$requirements = Get-Requirements -RequirementsPath $requirementsPath

$foundPhp = $null
if ([System.IO.File]::Exists($phpExe)) {
    $foundPhp = $phpExe
}

if (-not $foundPhp -and -not $ForceProjectRuntime) {
    $systemPhp = Find-SystemPhp
    if ($systemPhp -and (Test-Php -ExePath $systemPhp) -and (Test-PhpRequiredExtensions -ExePath $systemPhp -RequiredExtensions $requirements['PHP_REQUIRED_EXTENSIONS'])) {
        Write-Step "Using machine PHP: $systemPhp"
        $foundPhp = $systemPhp
    }
}

if (-not $foundPhp) {
    Write-Step 'Usable PHP missing. Download project-local PHP from internet.'
    Ensure-PhpDownload -Requirements $requirements -RuntimeDir $runtimeDir -PhpDir $phpDir -DownloadZip $downloadZip
    $foundPhp = $phpExe
}

if (-not [System.IO.File]::Exists($foundPhp)) {
    throw 'PHP setup finished but php.exe still missing.'
}

if (-not (Test-Php -ExePath $foundPhp)) {
    Write-Step 'PHP launch failed. Maybe VC++ runtime missing.'
    [void](Install-VcRedist -Requirements $requirements -VcRedistPath $vcRedist)
    if (-not (Test-Php -ExePath $foundPhp)) {
        if ($foundPhp -eq $phpExe) {
            Write-Step 'Project PHP still failed. Re-download project-local PHP.'
            Ensure-PhpDownload -Requirements $requirements -RuntimeDir $runtimeDir -PhpDir $phpDir -DownloadZip $downloadZip
            $foundPhp = $phpExe
        }
        if (-not (Test-Php -ExePath $foundPhp)) {
            throw 'PHP still cannot start after VC++ runtime install and download.'
        }
    }
}

if ($foundPhp -like ($phpDir + '*')) {
    Enable-PhpExtensions -PhpFolder $phpDir -Extensions (Get-EnabledExtensions -Requirements $requirements)
}

Write-Output $foundPhp
