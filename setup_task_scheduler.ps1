# ==================================================
# WOWCRM AUTOMATED TEST - TASK SCHEDULER SETUP
# ==================================================

if (-NOT ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]"Administrator")) {
    Write-Host "ERROR: This script requires Administrator privileges!" -ForegroundColor Red
    Write-Host "Please run PowerShell as Administrator and try again." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Task Scheduler Setup for WowCRM Tests" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$TaskName = "WowCRM Daily Test"
$TaskDescription = "Automated testing of Google Sheet Search page"
$ScriptPath = "C:\xampp\htdocs\wowcrm\run_test.bat"
$Time = "09:00"

Write-Host "[*] Task Configuration:" -ForegroundColor Yellow
Write-Host "    Name: $TaskName"
Write-Host "    Time: $Time (Daily)"
Write-Host "    Script: $ScriptPath"
Write-Host ""

$ExistingTask = Get-ScheduledTask -TaskName $TaskName -ErrorAction SilentlyContinue

if ($ExistingTask) {
    Write-Host "[!] Task already exists" -ForegroundColor Yellow
    $RemoveExisting = Read-Host "Remove and recreate? (Y/N)"

    if ($RemoveExisting -eq 'Y' -or $RemoveExisting -eq 'y') {
        Write-Host "[*] Removing existing task..." -ForegroundColor Yellow
        Unregister-ScheduledTask -TaskName $TaskName -Confirm:$false
        Write-Host "OK: Task removed" -ForegroundColor Green
    } else {
        Write-Host "Aborting..." -ForegroundColor Yellow
        exit
    }
}

Write-Host "[*] Creating task action..." -ForegroundColor Yellow
$Action = New-ScheduledTaskAction -Execute $ScriptPath -WorkingDirectory "C:\xampp\htdocs\wowcrm"

Write-Host "[*] Creating task trigger..." -ForegroundColor Yellow
$Trigger = New-ScheduledTaskTrigger -Daily -At $Time

Write-Host "[*] Creating task settings..." -ForegroundColor Yellow
$Settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable -MultipleInstances IgnoreNew

Write-Host "[*] Registering task..." -ForegroundColor Yellow
try {
    Register-ScheduledTask -TaskName $TaskName -Description $TaskDescription -Action $Action -Trigger $Trigger -Settings $Settings -RunLevel Highest -User "SYSTEM" -Force | Out-Null
    Write-Host "OK: Task registered successfully!" -ForegroundColor Green
}
catch {
    Write-Host "ERROR: $_" -ForegroundColor Red
    exit
}

Write-Host "[*] Enabling task..." -ForegroundColor Yellow
try {
    Enable-ScheduledTask -TaskName $TaskName -ErrorAction Stop | Out-Null
    Write-Host "OK: Task enabled!" -ForegroundColor Green
}
catch {
    Write-Host "WARNING: Could not enable task" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$Task = Get-ScheduledTask -TaskName $TaskName
Write-Host "Task Status:" -ForegroundColor Cyan
Write-Host "  Name: $($Task.TaskName)"
Write-Host "  State: $($Task.State)"

$TaskInfo = Get-ScheduledTask -TaskName $TaskName | Get-ScheduledTaskInfo
Write-Host "  Next Run: $($TaskInfo.NextRunTime)"

Write-Host ""
Write-Host "Results location:" -ForegroundColor Cyan
Write-Host "  C:\xampp\htdocs\wowcrm\wowcrm_test_results\" -ForegroundColor White
Write-Host ""

Write-Host "Manual test (anytime):" -ForegroundColor Cyan
Write-Host "  python C:\xampp\htdocs\wowcrm\test_seniorsearch.py" -ForegroundColor White
Write-Host ""
