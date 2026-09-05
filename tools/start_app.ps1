param(
    [Parameter(Mandatory = $true)]
    [string]$RootDir
)

$ErrorActionPreference = 'Stop'
try {
    Import-Module Microsoft.PowerShell.Utility -ErrorAction SilentlyContinue
} catch {
}

function Write-Step {
    param([string]$Message)
    [Console]::WriteLine($Message)
}

function Combine-Path2 {
    param(
        [string]$Left,
        [string]$Right
    )
    return [System.IO.Path]::Combine($Left, $Right)
}

function Ensure-Directory {
    param([string]$PathValue)
    if (-not [System.IO.Directory]::Exists($PathValue)) {
        [System.IO.Directory]::CreateDirectory($PathValue) | Out-Null
    }
}

function Write-AllLinesAscii {
    param(
        [string]$PathValue,
        [string[]]$Lines
    )
    $encoding = [System.Text.ASCIIEncoding]::new()
    [System.IO.File]::WriteAllLines($PathValue, $Lines, $encoding)
}

function Remove-FileSafe {
    param([string]$PathValue)
    if ([System.IO.File]::Exists($PathValue)) {
        [System.IO.File]::Delete($PathValue)
    }
}

function Quote-ProcessArgument {
    param([string]$Value)
    if ($null -eq $Value) { return '""' }
    return '"' + ($Value -replace '\\', '\\' -replace '"', '\"') + '"'
}

function Start-DetachedProcess {
    param(
        [string]$FilePath,
        [string[]]$Arguments,
        [string]$WorkingDirectory
    )

    $info = [System.Diagnostics.ProcessStartInfo]::new()
    $info.FileName = $FilePath
    $info.WorkingDirectory = $WorkingDirectory
    $info.UseShellExecute = $true
    $info.Arguments = (($Arguments | ForEach-Object { Quote-ProcessArgument $_ }) -join ' ')
    [void][System.Diagnostics.Process]::Start($info)
}

function Open-Url {
    param([string]$Url)

    try {
        $info = [System.Diagnostics.ProcessStartInfo]::new()
        $info.FileName = $Url
        $info.UseShellExecute = $true
        [void][System.Diagnostics.Process]::Start($info)
    } catch {
        Write-Step ("Open browser manually: {0}" -f $Url)
    }
}

function Get-Requirements {
    param([string]$RequirementsPath)

    $settings = @{
        APP_NAME = 'Hospital Management System'
        DATA_MODE = 'json'
        PHP_REQUIRED_EXTENSIONS = 'json,curl,fileinfo,gd,mbstring,openssl'
        PORT_START = '8000'
        PORT_END = '8100'
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
            if ($key) { $settings[$key] = $value }
        }
    }

    return $settings
}

function Get-FreePort {
    param(
        [int]$Start,
        [int]$End
    )

    for ($p = $Start; $p -le $End; $p++) {
        $busy = $false
        if (Get-Command Get-NetTCPConnection -ErrorAction SilentlyContinue) {
            $busy = [bool](Get-NetTCPConnection -State Listen -LocalPort $p -ErrorAction SilentlyContinue)
        } else {
            $listener = $null
            try {
                $listener = [System.Net.Sockets.TcpListener]::new([System.Net.IPAddress]::Parse('127.0.0.1'), $p)
                $listener.Start()
            } catch {
                $busy = $true
            } finally {
                if ($listener -ne $null) {
                    $listener.Stop()
                }
            }
        }
        if (-not $busy) {
            return $p
        }
    }
    return $null
}

function Test-PhpExtensions {
    param(
        [string]$PhpExe,
        [string]$RequiredExtensions
    )

    $checkPath = Combine-Path2 $env:TEMP ("hms_check_ext_{0}.php" -f ([guid]::NewGuid().ToString('N')))
    $scriptLines = @(
        '<?php'
        '$raw = getenv("HMS_REQUIRED_EXTENSIONS");'
        'if ($raw === false || $raw === "") { $raw = "json,curl,fileinfo,gd,mbstring,openssl"; }'
        '$required = array_filter(array_map("trim", explode(",", $raw)));'
        '$missing = array();'
        'foreach ($required as $ext) { if (!extension_loaded($ext)) { $missing[] = $ext; } }'
        'if ($missing) { fwrite(STDERR, "Missing PHP extensions: " . implode(", ", $missing) . PHP_EOL); exit(2); }'
        'echo "PHP extension check passed." . PHP_EOL;'
    )
    Write-AllLinesAscii -PathValue $checkPath -Lines $scriptLines

    try {
        $info = [System.Diagnostics.ProcessStartInfo]::new()
        $info.FileName = $PhpExe
        $info.Arguments = Quote-ProcessArgument $checkPath
        $info.UseShellExecute = $false
        $info.RedirectStandardOutput = $false
        $info.RedirectStandardError = $false
        $info.EnvironmentVariables['HMS_REQUIRED_EXTENSIONS'] = $RequiredExtensions
        $process = [System.Diagnostics.Process]::Start($info)
        $process.WaitForExit()
        if ($process.ExitCode -ne 0) {
            throw "PHP extension setup failed."
        }
    } finally {
        Remove-FileSafe -PathValue $checkPath
    }
}

function New-RouterFile {
    param([string]$RootPath)

    $routerPath = Combine-Path2 $env:TEMP ("hms_router_{0}.php" -f ([guid]::NewGuid().ToString('N')))
    $routerLines = @(
        '<?php'
        '$root = getenv("HMS_ROOT");'
        '$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);'
        '$file = $root . $path;'
        'if ($path !== "/" && file_exists($file) && !is_dir($file)) { return false; }'
        'chdir($root);'
        'require $root . "/index.php";'
    )
    Write-AllLinesAscii -PathValue $routerPath -Lines $routerLines
    return $routerPath
}

$requirementsPath = Combine-Path2 $RootDir 'requirements.txt'
$bootstrapPath = Combine-Path2 $RootDir 'tools\bootstrap_php.ps1'
$requirements = Get-Requirements -RequirementsPath $requirementsPath
$appName = $requirements['APP_NAME']
$dataMode = $requirements['DATA_MODE']
$requiredExtensions = $requirements['PHP_REQUIRED_EXTENSIONS']
$portStart = [int]$requirements['PORT_START']
$portEnd = [int]$requirements['PORT_END']

Write-Step ("[{0}] bootstrap start." -f $appName)
Write-Step ("Using requirements from ""{0}""" -f $requirementsPath)

if (-not [System.IO.File]::Exists($bootstrapPath)) {
    throw ("Missing bootstrap helper: {0}" -f $bootstrapPath)
}

$phpExe = & $bootstrapPath -RootDir $RootDir
if ([string]::IsNullOrWhiteSpace($phpExe)) {
    throw 'PHP bootstrap failed.'
}

Write-Step ("PHP ready: ""{0}""" -f $phpExe)

Ensure-Directory (Combine-Path2 $RootDir 'application\cache\sessions')
Ensure-Directory (Combine-Path2 $RootDir 'application\data')
Ensure-Directory (Combine-Path2 $RootDir 'uploads')

Test-PhpExtensions -PhpExe $phpExe -RequiredExtensions $requiredExtensions

$port = Get-FreePort -Start $portStart -End $portEnd
if (-not $port) {
    throw ("Free port not found in range {0}-{1}." -f $portStart, $portEnd)
}

$routerPath = New-RouterFile -RootPath $RootDir

try {
    $env:HMS_ROOT = $RootDir
    $env:CI_ENV = 'development'

    Write-Step ("Starting app on http://127.0.0.1:{0}/" -f $port)
    $args = @('-S', "127.0.0.1:$port", '-t', $RootDir, $routerPath)
    Start-DetachedProcess -FilePath $phpExe -Arguments $args -WorkingDirectory $RootDir
    Start-Sleep -Seconds 2
    Open-Url ("http://127.0.0.1:{0}/" -f $port)

    Write-Step ("App ready: http://127.0.0.1:{0}/" -f $port)
    Write-Step ("Data mode: {0}" -f $dataMode)
    Write-Step 'Close PHP process window or Task Manager entry to stop app.'
} finally {
    [System.Environment]::SetEnvironmentVariable('HMS_ROOT', $null, 'Process')
    [System.Environment]::SetEnvironmentVariable('CI_ENV', $null, 'Process')
}
