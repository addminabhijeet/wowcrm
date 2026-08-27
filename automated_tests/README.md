# WowCRM Automated Testing Suite

Comprehensive automated testing for WowCRM Google Sheet Search page with full Chrome UI cleanup.

## 📁 Folder Structure

```
automated_tests/
├── test_seniorsearch.py              # Main test script
├── test_search_functionality.py      # Additional search tests (optional)
├── TEST_SCRIPT_IMPROVEMENTS.md       # Detailed improvements documentation
├── README.md                         # This file
└── results/                          # Test results and screenshots
    ├── results_*.json                # Test result reports
    ├── test_*.log                    # Detailed test logs
    ├── screenshot_page_loaded_*.png  # Page load screenshots
    ├── screenshot_collapse_*.png     # Collapse functionality screenshots
    ├── after_login.png               # Post-login verification
    └── notification_check.png        # Chrome UI verification
```

## 🚀 Quick Start

### Prerequisites
```bash
pip install selenium pillow
```

### Set Environment Variables (Windows PowerShell)
```powershell
$env:WOWCRM_EMAIL="anuvabdasgupta@thetechsystem.com"
$env:WOWCRM_PASSWORD="Oswin@2026"
```

### Run Tests
```bash
cd C:\xampp\htdocs\wowcrm\automated_tests
python test_seniorsearch.py
```

## ✅ Features

### Chrome UI Enhancements
- ✅ **Automation Bar Removed** - "Chrome is being controlled..." message hidden
- ✅ **Password Dialog Disabled** - "Save password?" prompts eliminated
- ✅ **Notifications Blocked** - Chrome notification popups suppressed
- ✅ **Clean Environment** - Professional, distraction-free testing

### Test Coverage
- ✅ **Page Element Validation** (5 tests)
  - Table exists
  - Table headers present
  - Data rows loaded
  - Search input found
  - Pagination element exists

- ✅ **Login Verification** (1 test)
  - Credentials accepted
  - Redirect successful

- ✅ **Navigation Check** (1 test)
  - Test page loads correctly

- ✅ **Collapse Functionality** (1 test)
  - Rows expand/collapse properly

- ✅ **CSS Styling** (2 tests)
  - Table layout is fixed
  - Header is sticky

**Total: 10 Tests**

## 📊 Test Results

Results are saved to `results/` folder:

### Result Files
- `results_YYYYMMDD_HHMMSS.json` - Structured test results
- `test_YYYYMMDD_HHMMSS.log` - Detailed test logs

### Example Result
```json
{
  "timestamp": "2026-08-28T02:59:02.080785",
  "status": "PASSED",
  "tests_passed": 10,
  "tests_failed": 0,
  "errors": []
}
```

### Screenshots
- `screenshot_page_loaded_*.png` - Initial page state
- `screenshot_collapse_expanded_*.png` - Row expansion state
- `after_login.png` - Post-login verification
- `notification_check.png` - Chrome UI verification

## 🔧 Test Methods

### Core Test Methods

#### `setup_driver()`
Initializes Chrome WebDriver with UI enhancements:
- Disables automation detection bar
- Disables password manager prompts
- Disables notification popups

#### `maximize_window_and_close_notifications()`
Maximizes browser window immediately after setup for full-screen testing.

#### `validate_credentials()`
Verifies email/password environment variables are set.

#### `login()`
Authenticates to WowCRM with provided credentials.

#### `navigate_to_test_page()`
Loads the Google Sheet Search test page.

#### `test_page_elements()`
Validates critical page components (5 tests).

#### `test_collapse_functionality()`
Tests row expand/collapse behavior (1 test).

#### `test_css_styling()`
Verifies CSS styling is applied correctly (2 tests).

#### `close_password_save_dialog()`
Backup safety method to close password dialogs if they appear.

## 📝 Configuration

### Chrome Options Applied
```python
# Disable automation detection
--disable-blink-features=AutomationControlled
excludeSwitches: ["enable-automation"]
useAutomationExtension: false

# Disable password manager
credentials_enable_service: false
profile.password_manager_enabled: false

# Disable notifications
profile.default_content_setting_values.notifications: 2
```

## 🛠️ Troubleshooting

### Chrome Automation Bar Still Visible
1. Verify Chrome options in `setup_driver()` method
2. Check Chrome version compatibility
3. Clear Chrome cache: `chrome://settings/clearBrowserData`
4. Run as administrator

### Password Dialog Appears
1. Ensure `close_password_save_dialog()` is called after login
2. Check that Chrome preferences are applied
3. Verify Chrome settings are not overridden by policies

### Tests Fail to Run
1. Verify ChromeDriver is installed and in PATH
2. Check environment variables are set correctly
3. Ensure XAMPP/WowCRM server is running
4. Check `test_*.log` files in `results/` folder

## 📚 Documentation

- **TEST_SCRIPT_IMPROVEMENTS.md** - Detailed technical improvements
- **results/** - All test runs, screenshots, and logs
- **test_seniorsearch.py** - Commented source code

## 🔄 Automation

### Run Daily with Windows Task Scheduler
```powershell
# From PowerShell as Administrator:
$TaskName = "WowCRM Daily Test"
$TaskPath = "C:\xampp\htdocs\wowcrm\automated_tests\test_seniorsearch.py"

$Action = New-ScheduledTaskAction -Execute "python" -Argument $TaskPath
$Trigger = New-ScheduledTaskTrigger -Daily -At "09:00"
Register-ScheduledTask -TaskName $TaskName -Action $Action -Trigger $Trigger
```

### View Results
```powershell
# Latest test result
Get-ChildItem -Path "results\results_*.json" | Sort-Object LastWriteTime -Desc | Select-Object -First 1 | Get-Content | ConvertFrom-Json
```

## 📞 Support

### Common Issues

| Issue | Solution |
|-------|----------|
| Tests timeout | Increase wait time in WebDriverWait |
| Screenshots blurry | Increase window size in chrome_options |
| Login fails | Verify credentials are correct |
| Page not found | Ensure WowCRM server is running |

### View Logs
```powershell
# Latest test log
Get-ChildItem -Path "results\test_*.log" | Sort-Object LastWriteTime -Desc | Select-Object -First 1 | Get-Content
```

## 🎯 Next Steps

1. ✅ Run initial test: `python test_seniorsearch.py`
2. ✅ Review results in `results/` folder
3. ✅ Schedule daily automated runs
4. ✅ Monitor test results over time
5. ✅ Investigate any test failures

## 📋 Checklist

- [ ] Environment variables set
- [ ] ChromeDriver installed
- [ ] WowCRM server running
- [ ] First test run successful
- [ ] Results reviewed
- [ ] Scheduled task configured (optional)

## 📅 Last Updated

August 28, 2026

## 📄 License

Part of WowCRM Project
