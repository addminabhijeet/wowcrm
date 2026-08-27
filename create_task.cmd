@echo off
echo.
echo ========================================
echo   WowCRM Task Scheduler Setup
echo ========================================
echo.

REM Create scheduled task to run daily at 9:00 AM
schtasks /create /tn "WowCRM Daily Test" /tr "C:\xampp\htdocs\wowcrm\run_test.bat" /sc daily /st 09:00 /ru SYSTEM /f

if %errorlevel% equ 0 (
    echo.
    echo [OK] Task created successfully!
    echo [OK] Task will run daily at 9:00 AM
    echo.
) else (
    echo.
    echo [ERROR] Failed to create task
    echo.
)

echo.
echo Task Name: WowCRM Daily Test
echo Schedule: Daily at 09:00 AM
echo Script: C:\xampp\htdocs\wowcrm\run_test.bat
echo Results: C:\xampp\htdocs\wowcrm\wowcrm_test_results\
echo.
echo Manually run test anytime with:
echo   python C:\xampp\htdocs\wowcrm\test_seniorsearch.py
echo.
pause
