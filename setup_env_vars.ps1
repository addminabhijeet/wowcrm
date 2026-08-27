# ==================================================
# WOWCRM AUTOMATED TEST - ENVIRONMENT SETUP SCRIPT
# ==================================================
# This script sets up environment variables for secure credential storage
# Run with: powershell -ExecutionPolicy Bypass -File setup_env_vars.ps1

# Requires Administrator Privileges
if (-NOT ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]"Administrator")) {
    Write-Host "ERROR: This script requires Administrator privileges!" -ForegroundColor Red
    Write-Host "Please run PowerShell as Administrator and try again." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  WOWCRM Test - Environment Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Set email
$email = "anuvabdasgupta@thetechsystem.com"
Write-Host "[*] Setting WOWCRM_EMAIL environment variable..." -ForegroundColor Yellow
[Environment]::SetEnvironmentVariable("WOWCRM_EMAIL", $email, "User")
Write-Host "✓ Email set: $email" -ForegroundColor Green

# Set password
$password = "Oswin@2026"
Write-Host "[*] Setting WOWCRM_PASSWORD environment variable..." -ForegroundColor Yellow
[Environment]::SetEnvironmentVariable("WOWCRM_PASSWORD", $password, "User")
Write-Host "✓ Password set: (hidden for security)" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verify setup
Write-Host "[*] Verifying environment variables..." -ForegroundColor Yellow
$env:WOWCRM_EMAIL = [Environment]::GetEnvironmentVariable("WOWCRM_EMAIL", "User")
$env:WOWCRM_PASSWORD = [Environment]::GetEnvironmentVariable("WOWCRM_PASSWORD", "User")

if ($env:WOWCRM_EMAIL -eq $email -and $env:WOWCRM_PASSWORD -eq $password) {
    Write-Host "✓ Environment variables verified successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "You can now run the automated tests:" -ForegroundColor Cyan
    Write-Host "  python test_seniorsearch.py" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host "ERROR: Environment variables were not set correctly!" -ForegroundColor Red
}

Write-Host "Press Enter to close this window..."
Read-Host
