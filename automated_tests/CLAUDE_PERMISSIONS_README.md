# Claude Desktop Permission Handler

Automated script to handle Claude Desktop application permission dialogs.

## Purpose

When Claude Desktop asks for permissions (clipboard, microphone, camera, notifications, etc.), this script automatically clicks the "Allow" button.

## How It Works

The script uses multiple methods to ensure the "Allow" button is clicked:

1. **Tab Navigation** - Uses Tab key to navigate to the Allow button, then Space to click
2. **Position Detection** - Scans common dialog positions for the Allow button
3. **Keyboard Shortcut** - Tries Alt+Y (common for "Yes"/"Allow")
4. **Escape Handling** - Handles some dialogs that may need escape

## Usage

### Run Standalone
```bash
python claude_allow_permissions.py
```

### Run Before Tests
```bash
# First handle permissions
python claude_allow_permissions.py

# Then run automated tests
$env:WOWCRM_EMAIL="anuvabdasgupta@thetechsystem.com"
$env:WOWCRM_PASSWORD="Oswin@2026"
python test_seniorsearch.py
```

### Run in Sequence
```bash
# Create a batch script or PowerShell script that runs both
python claude_allow_permissions.py && python test_seniorsearch.py
```

## Methods Used

### Method 1: Tab + Space (Primary)
- Press Tab to navigate to Allow button (1-2 presses)
- Press Space to activate the button
- Most reliable for standard Windows dialogs

### Method 2: Position Detection
- Takes screenshot of common Allow button positions
- Right side of dialogs (1850, 500), (1850, 550), etc.
- Useful for visual verification

### Method 3: Alt+Y Shortcut
- Alt+Y keyboard shortcut
- Common alternative for "Yes"/"Allow" buttons
- Works on many system dialogs

### Method 4: Escape Recovery
- Presses Escape to handle edge cases
- Allows dialog to be processed
- Fallback for non-standard dialogs

## Output

The script creates logs in:
- `wowcrm_test_results/claude_permissions.log` - Detailed log file
- Console output - Real-time feedback

## Requirements

```
pyautogui>=0.9.53
```

Install with:
```bash
pip install pyautogui
```

## Notes

- Run BEFORE Claude Desktop permission dialogs appear
- Or run while dialogs are active
- Works on Windows systems
- No modification to previous code or test logic
- Safe to run multiple times

## Troubleshooting

If the "Allow" button is not clicked:

1. **Manual Fallback**: Press Tab + Space when you see the dialog
2. **Check Encoding**: Ensure terminal supports UTF-8
3. **Try Alt+Y**: Use Alt+Y as alternative shortcut
4. **Visual Confirmation**: Look at logs in `wowcrm_test_results/` directory

## Integration with Test Suite

To integrate with automated testing:

```bash
# PowerShell: Run both scripts
python claude_allow_permissions.py; $delay = 1; Start-Sleep -Seconds $delay; python test_seniorsearch.py
```

## Created
2026-08-28 - Version 1.0
