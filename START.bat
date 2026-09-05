@echo off
setlocal EnableExtensions

cd /d "%~dp0"
set "ROOT=%CD%"
set "PS_EXE="
set "START_PS1=%ROOT%\tools\start_app.ps1"

call :find_powershell
if not defined PS_EXE (
  echo PowerShell missing. Windows PowerShell or PowerShell 7 required.
  pause
  exit /b 1
)

if not exist "%START_PS1%" (
  echo Missing launcher script: "%START_PS1%"
  pause
  exit /b 1
)

"%PS_EXE%" -NoLogo -NoProfile -ExecutionPolicy Bypass -File "%START_PS1%" -RootDir "%ROOT%"
set "RUN_STATUS=%ERRORLEVEL%"
if not "%RUN_STATUS%"=="0" (
  pause
  exit /b %RUN_STATUS%
)

endlocal
exit /b 0

:find_powershell
set "PS_EXE="
for /f "delims=" %%F in ('where pwsh.exe 2^>nul') do (
  if not defined PS_EXE set "PS_EXE=%%F"
)
if defined PS_EXE exit /b 0
if exist "%ProgramFiles%\PowerShell\7\pwsh.exe" set "PS_EXE=%ProgramFiles%\PowerShell\7\pwsh.exe"
if defined PS_EXE exit /b 0
if exist "%ProgramFiles(x86)%\PowerShell\7\pwsh.exe" set "PS_EXE=%ProgramFiles(x86)%\PowerShell\7\pwsh.exe"
if defined PS_EXE exit /b 0
for /f "delims=" %%F in ('where powershell.exe 2^>nul') do (
  if not defined PS_EXE set "PS_EXE=%%F"
)
if defined PS_EXE exit /b 0
if exist "%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe" set "PS_EXE=%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe"
exit /b 0
