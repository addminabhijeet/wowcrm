@echo off
REM ==================================================
REM WOWCRM AUTOMATED TEST RUNNER
REM Run this daily to test Google Sheet Search page
REM ==================================================

echo.
echo ========================================
echo   WOWCRM Automated Test Runner
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Python is not installed or not in PATH
    echo Please install Python from: https://www.python.org/downloads/
    echo Make sure to check "Add Python to PATH" during installation
    pause
    exit /b 1
)

echo [+] Python found
echo [+] Starting tests...
echo.

REM Change to script directory
cd /d "%~dp0"

REM Run the test script
python test_seniorsearch.py

REM Capture exit code
if errorlevel 1 (
    echo.
    echo ERROR: Tests failed or encountered an error
    echo Check logs in: wowcrm_test_results\
) else (
    echo.
    echo SUCCESS: All tests completed
    echo Results saved in: wowcrm_test_results\
)

echo.
pause
