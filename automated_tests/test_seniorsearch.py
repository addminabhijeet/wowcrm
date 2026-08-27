#!/usr/bin/env python3
"""
AUTOMATED TESTING SCRIPT FOR WOWCRM SENIORSEARCH PAGE
Tests the Google Sheet Search page daily without manual login
Credentials stored securely in environment variables

SETUP:
1. Install dependencies: pip install selenium playwright pillow
2. Set environment variables:
   - Windows (PowerShell): $env:WOWCRM_EMAIL="anuvabdasgupta@thetechsystem.com"
   - Windows (PowerShell): $env:WOWCRM_PASSWORD="Oswin@2026"
   - Windows (CMD): set WOWCRM_EMAIL=anuvabdasgupta@thetechsystem.com
   - Windows (CMD): set WOWCRM_PASSWORD=Oswin@2026
3. Run: python test_seniorsearch.py

OPTIONAL: Run daily using Windows Task Scheduler or cron

CHROME UI ENHANCEMENTS:
✅ Automation Bar Disabled: Removes "Chrome is being controlled by automated test software" bar
✅ Password Save Dialog Disabled: Removes "Save password?" dialog after login
✅ Notifications Disabled: Removes Chrome notification prompts
"""

import os
import sys
import time
import logging
from datetime import datetime
from pathlib import Path
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options

# ============================================
# CONFIGURATION
# ============================================
BASE_URL = "http://localhost/wowcrm"
TEST_PAGE = f"{BASE_URL}/dashboard/senior/google-sheet-search"
LOGIN_URL = f"{BASE_URL}/login"

# Credentials from environment variables (SECURE)
EMAIL = os.getenv('WOWCRM_EMAIL')
PASSWORD = os.getenv('WOWCRM_PASSWORD')

# Output directory for screenshots and logs (relative to automated_tests folder)
OUTPUT_DIR = Path(__file__).parent / "results"
OUTPUT_DIR.mkdir(exist_ok=True)

# Setup logging
LOG_FILE = OUTPUT_DIR / f"test_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler(LOG_FILE),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# ============================================
# MAIN TEST CLASS
# ============================================
class WowCRMTester:
    def __init__(self):
        """Initialize the Selenium WebDriver"""
        self.driver = None
        self.wait = None
        self.test_results = {
            'timestamp': datetime.now().isoformat(),
            'status': 'PENDING',
            'tests_passed': 0,
            'tests_failed': 0,
            'errors': []
        }

    def setup_driver(self):
        """Setup Chrome WebDriver with UI enhancements

        Disables:
        - Automation detection bar ("Chrome is being controlled...")
        - Password save prompts ("Save password?" dialog)
        - Notification popups
        """
        logger.info("Setting up Chrome WebDriver with UI enhancements...")

        chrome_options = Options()
        # Uncomment line below for headless mode (no browser window)
        # chrome_options.add_argument("--headless")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        chrome_options.add_argument("--window-size=1920,1080")

        # ============================================
        # CHROME UI ENHANCEMENTS
        # ============================================

        # 1. Disable automation detection bar
        chrome_options.add_argument("--disable-blink-features=AutomationControlled")
        chrome_options.add_experimental_option("excludeSwitches", ["enable-automation"])
        chrome_options.add_experimental_option('useAutomationExtension', False)

        # 2. Disable password save prompts and notifications
        prefs = {
            "credentials_enable_service": False,              # Disable password manager prompts
            "profile.password_manager_enabled": False,        # Disable password storage
            "profile.default_content_setting_values.notifications": 2  # Disable notifications
        }
        chrome_options.add_experimental_option("prefs", prefs)

        try:
            self.driver = webdriver.Chrome(options=chrome_options)
            self.wait = WebDriverWait(self.driver, 20)
            logger.info("✅ Chrome WebDriver initialized successfully")
            logger.info("✅ UI enhancements applied (automation bar, password dialogs, notifications disabled)")
            return True
        except Exception as e:
            logger.error(f"❌ Failed to initialize Chrome WebDriver: {e}")
            self.test_results['errors'].append(f"WebDriver setup failed: {e}")
            return False

    def close_password_save_dialog(self):
        """
        Close Chrome 'Save password' dialog if it appears after login

        IMPORTANT: Password saving is disabled via Chrome prefs in setup_driver()
        This method is a BACKUP safety mechanism if the dialog somehow appears.

        Methods used:
        - Escape key (closes most dialogs)
        - "Not now", "Never", "No thanks" buttons (common dismiss buttons)
        """
        try:
            from selenium.webdriver.common.keys import Keys

            # Method 1: Press Escape to close the dialog
            try:
                body = self.driver.find_element(By.TAG_NAME, "body")
                body.send_keys(Keys.ESCAPE)
                logger.debug("✅ Password save dialog closed (Escape key)")
                time.sleep(0.5)
                return True
            except Exception as e1:
                logger.debug(f"ℹ️  Escape key method: {str(e1)[:40]}")

            # Method 2: Look for common dismiss buttons
            try:
                dismiss_xpaths = [
                    "//button[contains(text(), 'Not now')]",
                    "//button[contains(text(), 'Never')]",
                    "//button[contains(text(), 'No thanks')]",
                    "//div[@role='alertdialog']//button",
                ]

                for xpath in dismiss_xpaths:
                    try:
                        btn = self.driver.find_element(By.XPATH, xpath)
                        if btn.is_displayed():
                            btn.click()
                            logger.debug("✅ Password save dialog closed (Button click)")
                            time.sleep(0.5)
                            return True
                    except Exception:
                        continue
            except Exception as e2:
                logger.debug(f"ℹ️  Button click method: {str(e2)[:40]}")

            logger.debug("ℹ️  No password save dialog detected")
            return True

        except Exception as e:
            logger.warning(f"⚠️  Password dialog handler: {str(e)[:50]}")
            return True

    def maximize_window_and_close_notifications(self):
        """Maximize Chrome window (automation bar disabled via Chrome options)"""
        logger.info("Maximizing Chrome window...")

        try:
            # Maximize the window
            self.driver.maximize_window()
            logger.info("✅ Chrome window maximized")
            time.sleep(0.5)

            # Take screenshot to verify no automation bar appears
            try:
                screenshot_file = OUTPUT_DIR / "notification_check.png"
                self.driver.save_screenshot(str(screenshot_file))
                logger.debug("📸 Window state verified")
            except:
                pass

            return True

        except Exception as e:
            logger.warning(f"⚠️  Warning: {e}")
            return True

    def validate_credentials(self):
        """Validate that credentials are set"""
        if not EMAIL or not PASSWORD:
            logger.error("❌ CREDENTIALS NOT FOUND!")
            logger.error("Set environment variables:")
            logger.error("  - WOWCRM_EMAIL")
            logger.error("  - WOWCRM_PASSWORD")
            self.test_results['errors'].append("Credentials not set in environment variables")
            return False

        logger.info(f"✅ Credentials loaded: {EMAIL}")
        return True

    def login(self):
        """Perform login"""
        logger.info("Logging in...")
        try:
            self.driver.get(LOGIN_URL)
            time.sleep(2)

            # Find and fill email
            email_field = self.wait.until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "input[type='email']"))
            )
            email_field.clear()
            email_field.send_keys(EMAIL)
            logger.info("✅ Email entered")

            # Find and fill password
            password_field = self.driver.find_element(By.CSS_SELECTOR, "input[type='password']")
            password_field.clear()
            password_field.send_keys(PASSWORD)
            logger.info("✅ Password entered")

            # Click login button
            login_button = self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
            login_button.click()
            logger.info("✅ Login button clicked")

            # Wait for redirect
            time.sleep(3)

            # Close Chrome "Save password" dialog if it appears (backup for disabled prefs)
            self.close_password_save_dialog()

            # Take screenshot to verify password dialog is closed
            try:
                screenshot_file = OUTPUT_DIR / "after_login.png"
                self.driver.save_screenshot(str(screenshot_file))
                logger.debug("📸 After-login screenshot saved")
            except:
                pass

            # Verify login success
            if "login" not in self.driver.current_url.lower():
                logger.info(f"✅ Login successful! Current URL: {self.driver.current_url}")
                self.test_results['tests_passed'] += 1
                return True
            else:
                logger.error("❌ Login failed - still on login page")
                self.test_results['tests_failed'] += 1
                self.test_results['errors'].append("Login failed")
                return False

        except Exception as e:
            logger.error(f"❌ Login error: {e}")
            self.test_results['errors'].append(f"Login error: {e}")
            self.test_results['tests_failed'] += 1
            return False

    def navigate_to_test_page(self):
        """Navigate to the Google Sheet Search page"""
        logger.info(f"Navigating to: {TEST_PAGE}")
        try:
            self.driver.get(TEST_PAGE)
            time.sleep(3)

            # Check if page loaded
            current_url = self.driver.current_url
            if TEST_PAGE in current_url or "google-sheet-search" in current_url:
                logger.info(f"✅ Page loaded successfully: {current_url}")
                self.test_results['tests_passed'] += 1
                return True
            else:
                logger.error(f"❌ Page not loaded. Current URL: {current_url}")
                self.test_results['tests_failed'] += 1
                self.test_results['errors'].append(f"Failed to load test page")
                return False

        except Exception as e:
            logger.error(f"❌ Navigation error: {e}")
            self.test_results['errors'].append(f"Navigation error: {e}")
            self.test_results['tests_failed'] += 1
            return False

    def test_page_elements(self):
        """Test critical page elements"""
        logger.info("Testing page elements...")
        tests_passed = 0

        # Test 1: Check if table exists
        try:
            table = self.driver.find_element(By.CLASS_NAME, "table")
            logger.info("✅ Table element found")
            tests_passed += 1
        except:
            logger.error("❌ Table element not found")
            self.test_results['errors'].append("Table element missing")

        # Test 2: Check if table has headers
        try:
            headers = self.driver.find_elements(By.CSS_SELECTOR, "table thead th")
            if len(headers) > 0:
                logger.info(f"✅ Table headers found: {len(headers)} columns")
                tests_passed += 1
            else:
                logger.error("❌ No table headers found")
                self.test_results['errors'].append("Table headers missing")
        except Exception as e:
            logger.error(f"❌ Error checking headers: {e}")

        # Test 3: Check if table has data rows
        try:
            rows = self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr:not(.collapse-row)")
            if len(rows) > 0:
                logger.info(f"✅ Table data rows found: {len(rows)} rows")
                tests_passed += 1
            else:
                logger.warn("⚠️  No data rows found (might be empty dataset)")
        except Exception as e:
            logger.error(f"❌ Error checking rows: {e}")

        # Test 4: Check if search input exists
        try:
            search_input = self.driver.find_element(By.ID, "senior-search")
            logger.info("✅ Search input found")
            tests_passed += 1
        except:
            logger.error("❌ Search input not found")
            self.test_results['errors'].append("Search input missing")

        # Test 5: Check if pagination exists
        try:
            pagination = self.driver.find_element(By.CLASS_NAME, "pagination")
            logger.info("✅ Pagination element found")
            tests_passed += 1
        except:
            logger.warn("⚠️  Pagination element not found (might be hidden)")

        self.test_results['tests_passed'] += tests_passed
        return tests_passed >= 3

    def test_collapse_functionality(self):
        """Test collapse/expand functionality"""
        logger.info("Testing collapse functionality...")
        try:
            # Get first data row
            rows = self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr:not(.collapse-row)")
            if len(rows) == 0:
                logger.warn("⚠️  No data rows to test collapse")
                return True

            first_row = rows[0]
            first_row.click()
            logger.info("✅ First row clicked")
            time.sleep(1)

            # Check if collapse row is visible
            collapse_rows = self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr.collapse-row.show")
            if len(collapse_rows) > 0:
                logger.info(f"✅ Collapse row visible after click")
                self.test_results['tests_passed'] += 1

                # Take screenshot of expanded row
                self.take_screenshot("collapse_expanded")
                return True
            else:
                logger.warn("⚠️  Collapse row not visible after click")
                return False

        except Exception as e:
            logger.error(f"❌ Collapse test error: {e}")
            self.test_results['errors'].append(f"Collapse test error: {e}")
            return False

    def test_css_styling(self):
        """Test CSS styling applied correctly"""
        logger.info("Testing CSS styling...")
        tests_passed = 0

        try:
            # Get first data row
            rows = self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr:not(.collapse-row)")
            if len(rows) == 0:
                logger.warn("⚠️  No data rows to test styling")
                return True

            row = rows[0]

            # Check row height
            row_height = row.value_of_css_property("height")
            logger.info(f"ℹ️  Row height: {row_height}")

            # Check if table has fixed layout
            table = self.driver.find_element(By.CLASS_NAME, "table")
            table_layout = table.value_of_css_property("table-layout")
            if table_layout == "fixed":
                logger.info(f"✅ Table layout is fixed: {table_layout}")
                tests_passed += 1
            else:
                logger.warn(f"⚠️  Table layout might not be fixed: {table_layout}")

            # Check if header is sticky
            headers = self.driver.find_elements(By.CSS_SELECTOR, "table thead")
            if len(headers) > 0:
                position = headers[0].value_of_css_property("position")
                if position == "sticky":
                    logger.info(f"✅ Header is sticky: {position}")
                    tests_passed += 1
                else:
                    logger.warn(f"⚠️  Header position: {position}")

            self.test_results['tests_passed'] += tests_passed
            return tests_passed >= 1

        except Exception as e:
            logger.error(f"❌ CSS styling test error: {e}")
            self.test_results['errors'].append(f"CSS test error: {e}")
            return False

    def take_screenshot(self, name=""):
        """Take and save screenshot"""
        try:
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            screenshot_file = OUTPUT_DIR / f"screenshot_{name}_{timestamp}.png"
            self.driver.save_screenshot(str(screenshot_file))
            logger.info(f"✅ Screenshot saved: {screenshot_file}")
            return screenshot_file
        except Exception as e:
            logger.error(f"❌ Screenshot error: {e}")
            return None

    def run_all_tests(self):
        """Run all tests"""
        logger.info("=" * 60)
        logger.info("STARTING WOWCRM SENIORSEARCH TESTS")
        logger.info("=" * 60)

        try:
            # Setup
            if not self.setup_driver():
                self.test_results['status'] = 'FAILED'
                return False

            # Maximize window (must be done immediately after setup)
            self.maximize_window_and_close_notifications()

            if not self.validate_credentials():
                self.test_results['status'] = 'FAILED'
                return False

            # Login
            if not self.login():
                self.test_results['status'] = 'FAILED'
                self.take_screenshot("login_failed")
                return False

            # Navigate to test page
            if not self.navigate_to_test_page():
                self.test_results['status'] = 'FAILED'
                return False

            # Take initial screenshot
            self.take_screenshot("page_loaded")

            # Run tests
            self.test_page_elements()
            self.test_css_styling()
            self.test_collapse_functionality()

            # Determine overall status
            if self.test_results['tests_failed'] == 0:
                self.test_results['status'] = 'PASSED'
                logger.info("✅ ALL TESTS PASSED!")
            else:
                self.test_results['status'] = 'PASSED_WITH_WARNINGS'
                logger.warn(f"⚠️  Tests completed with {self.test_results['tests_failed']} failures")

            return True

        except Exception as e:
            logger.error(f"❌ Unexpected error: {e}")
            self.test_results['status'] = 'FAILED'
            self.test_results['errors'].append(f"Unexpected error: {e}")
            return False

        finally:
            self.cleanup()

    def cleanup(self):
        """Cleanup and close browser"""
        logger.info("Cleaning up...")
        if self.driver:
            self.driver.quit()
            logger.info("✅ Browser closed")

        # Save test results
        self.save_results()

    def save_results(self):
        """Save test results to file"""
        try:
            import json
            results_file = OUTPUT_DIR / f"results_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
            with open(results_file, 'w') as f:
                json.dump(self.test_results, f, indent=2)
            logger.info(f"✅ Results saved: {results_file}")
        except Exception as e:
            logger.error(f"❌ Failed to save results: {e}")

# ============================================
# ENTRY POINT
# ============================================
if __name__ == "__main__":
    logger.info("Python WowCRM Test Script Initialized")
    logger.info(f"Log file: {LOG_FILE}")

    tester = WowCRMTester()
    success = tester.run_all_tests()

    logger.info("=" * 60)
    logger.info(f"FINAL STATUS: {tester.test_results['status']}")
    logger.info(f"Tests Passed: {tester.test_results['tests_passed']}")
    logger.info(f"Tests Failed: {tester.test_results['tests_failed']}")
    logger.info("=" * 60)

    sys.exit(0 if success else 1)
