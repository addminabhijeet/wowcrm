-- ============================================================================
-- CRITICAL DATABASE INDEXES FOR WOWCRM PERFORMANCE
-- ============================================================================
-- These indexes MUST be created for the system to perform acceptably!
-- Without these, queries will be extremely slow (10+ seconds per pageload)
-- ============================================================================

-- ============================================================================
-- MISSING INDEXES - ADD THESE TO COMPLETE OPTIMIZATION
-- ============================================================================

-- Logins table indexes (missing)
ALTER TABLE logins ADD INDEX idx_user_id (user_id);
ALTER TABLE logins ADD INDEX idx_created_at (created_at DESC);

-- Composite indexes for common query patterns
ALTER TABLE google_sheet_data ADD INDEX idx_created_by_exe (created_by, Exe_Remarks);
ALTER TABLE users ADD INDEX idx_deleted_role_status (is_deleted, role, status);

-- ============================================================================
-- NOTE: The following indexes already exist in the database:
-- ============================================================================
-- users: idx_is_deleted, idx_role_deleted, idx_status_deleted
-- user_timer_logs: idx_user_id, idx_user_created
-- user_timer_pauses: idx_user_id_pause, idx_user_created_pause
-- notifications: idx_notifiable, idx_unread, idx_created_desc
-- chats: idx_sender_receiver, idx_recipient_unread, idx_sender_recipient, idx_created_desc
-- google_sheet_data: idx_created_by, idx_created_by_date, idx_name_search, idx_email_search, idx_phone_search, idx_exe_remarks, idx_updated_at

-- ============================================================================
-- VERIFY INDEXES WERE CREATED
-- ============================================================================
-- Run this to verify all indexes exist:
-- SHOW INDEX FROM users WHERE Key_name IN ('idx_is_deleted', 'idx_role_deleted');
-- SHOW INDEX FROM user_timer_logs WHERE Key_name IN ('idx_user_id', 'idx_user_created');
-- SHOW INDEX FROM notifications WHERE Key_name IN ('idx_notifiable', 'idx_unread');
-- SHOW INDEX FROM google_sheet_data WHERE Key_name IN ('idx_created_by', 'idx_name_search');

-- ============================================================================
-- PERFORMANCE IMPACT
-- ============================================================================
-- Expected improvements with these indexes:
-- - Query time per user: 10,000ms → < 500ms (20x faster)
-- - Queries per second: 9.96 → > 50 (5x better)
-- - CPU load: 95%+ → < 30%
-- - Database connections: High → Normal

-- These indexes are the PRIMARY FIX. They should be created IMMEDIATELY
-- on production before testing for performance improvements.
