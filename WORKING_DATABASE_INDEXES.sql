-- ============================================================================
-- WORKING DATABASE INDEXES FOR WOWCRM
-- ============================================================================
-- Fixed SQL syntax for all MySQL versions
-- ============================================================================

-- ============================================================================
-- Only add missing indexes (others already exist)
-- ============================================================================

-- Logins table indexes (if missing)
ALTER TABLE logins ADD INDEX IF NOT EXISTS idx_user_id (user_id);
ALTER TABLE logins ADD INDEX IF NOT EXISTS idx_created_at (created_at DESC);

-- Composite indexes for common query patterns
ALTER TABLE google_sheet_data ADD INDEX IF NOT EXISTS idx_created_by_exe (created_by, Exe_Remarks);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_deleted_role_status (is_deleted, role, status);

-- ============================================================================
-- VERIFY - Check which indexes exist
-- ============================================================================
SHOW INDEX FROM users;
SHOW INDEX FROM user_timer_logs;
SHOW INDEX FROM notifications;
SHOW INDEX FROM google_sheet_data;
SHOW INDEX FROM chat;
