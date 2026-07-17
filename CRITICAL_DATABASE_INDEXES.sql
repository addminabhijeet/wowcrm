-- ============================================================================
-- CRITICAL DATABASE INDEXES FOR WOWCRM PERFORMANCE
-- ============================================================================
-- These indexes MUST be created for the system to perform acceptably!
-- Without these, queries will be extremely slow (10+ seconds per pageload)
-- ============================================================================

-- ============================================================================
-- 1. USER TABLE INDEXES (used in almost every query)
-- ============================================================================
ALTER TABLE users ADD INDEX idx_is_deleted (is_deleted);
ALTER TABLE users ADD INDEX idx_role_deleted (role, is_deleted);
ALTER TABLE users ADD INDEX idx_status_deleted (status, is_deleted);

-- ============================================================================
-- 2. TIMER TABLES INDEXES (polled every 30 seconds - CRITICAL!)
-- ============================================================================
-- Most important: get latest timer per user
ALTER TABLE user_timer_logs ADD INDEX idx_user_id (user_id);
ALTER TABLE user_timer_logs ADD INDEX idx_user_created (user_id, created_at DESC);

-- For pause status checks
ALTER TABLE user_timer_pauses ADD INDEX idx_user_id_pause (user_id);
ALTER TABLE user_timer_pauses ADD INDEX idx_user_created_pause (user_id, created_at DESC);

-- ============================================================================
-- 3. NOTIFICATION TABLES INDEXES (polled every 60 seconds - CRITICAL!)
-- ============================================================================
ALTER TABLE notifications ADD INDEX idx_notifiable (notifiable_id, notifiable_role);
ALTER TABLE notifications ADD INDEX idx_unread (notifiable_id, read_at, created_at DESC);
ALTER TABLE notifications ADD INDEX idx_created_desc (created_at DESC);

-- ============================================================================
-- 4. GOOGLE SHEET DATA INDEXES (frequently searched)
-- ============================================================================
-- Critical for candidate searches
ALTER TABLE google_sheet_data ADD INDEX idx_created_by (created_by);
ALTER TABLE google_sheet_data ADD INDEX idx_created_by_date (created_by, `Date` DESC);

-- For search functionality
ALTER TABLE google_sheet_data ADD INDEX idx_name_search (Name);
ALTER TABLE google_sheet_data ADD INDEX idx_email_search (Email_Address);
ALTER TABLE google_sheet_data ADD INDEX idx_phone_search (Phone_Number);

-- For common filters
ALTER TABLE google_sheet_data ADD INDEX idx_exe_remarks (Exe_Remarks);
ALTER TABLE google_sheet_data ADD INDEX idx_updated_at (updated_at DESC);

-- ============================================================================
-- 5. CHAT TABLES INDEXES (for message queries)
-- ============================================================================
ALTER TABLE chats ADD INDEX idx_sender_receiver (sender_id, receiver_id);
ALTER TABLE chats ADD INDEX idx_recipient_unread (receiver_id, is_read, created_at DESC);
ALTER TABLE chats ADD INDEX idx_sender_recipient (sender_id, receiver_id, created_at DESC);
ALTER TABLE chats ADD INDEX idx_created_desc (created_at DESC);

-- ============================================================================
-- 6. OTHER IMPORTANT TABLES
-- ============================================================================
-- Payments
ALTER TABLE payments ADD INDEX idx_user_id (user_id);
ALTER TABLE payments ADD INDEX idx_created_at (created_at DESC);

-- Attendance
ALTER TABLE attendance ADD INDEX idx_user_id_date (user_id, attendance_date);

-- Logins
ALTER TABLE logins ADD INDEX idx_user_id (user_id);
ALTER TABLE logins ADD INDEX idx_created_at (created_at DESC);

-- ============================================================================
-- 7. COMPOSITE INDEXES FOR COMMON QUERY PATTERNS
-- ============================================================================
-- For queries that filter by multiple columns
ALTER TABLE google_sheet_data ADD INDEX idx_created_by_exe (created_by, Exe_Remarks);
ALTER TABLE users ADD INDEX idx_deleted_role_status (is_deleted, role, status);

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
