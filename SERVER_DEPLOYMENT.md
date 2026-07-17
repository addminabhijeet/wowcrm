# WowCRM Performance Fix - Server Deployment Guide

## What Was Fixed in Code

✅ **Commit:** `1e0a8c90f` - Eliminated N+1 query bottlenecks

### Fixed Controllers:
1. **CandidateController.accountant()** - Was querying User table once per displayed row
   - Before: 10 rows = 10 extra database queries per request
   - After: Single query + array lookups

2. **TimerController.seniorTimers()** - Was querying timer logs per junior
   - Before: 20 juniors = 20 extra database queries
   - After: Batch fetch all timers at once

3. **TimerController.allseniorTimers()** - Same optimization
4. **TimerController.allJuniorTimers()** - Same optimization

### Expected Improvement After Deployment:
- **Database queries per pageload:** Reduced by 50-70%
- **Response time:** Should decrease from 10s to 1-2s
- **Throughput:** Should improve from 10 req/sec to 50+ req/sec

---

## Server-Side Deployment Steps

### Step 1: Pull Latest Code
```bash
cd /var/www/norloxsolutionscrm.com/wowcrm
git pull origin main
```

### Step 2: Run Database Indexes (CRITICAL)
These indexes are essential for the optimization to be effective:

```bash
mysql -u root -p << 'EOF'
-- Essential indexes for frequently polled queries
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_is_deleted (is_deleted);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_status_deleted (status, is_deleted);

-- Timer endpoints (polled every 30s)
ALTER TABLE user_timer_logs ADD INDEX IF NOT EXISTS idx_user_id_latest (user_id, created_at DESC);
ALTER TABLE user_timer_pause ADD INDEX IF NOT EXISTS idx_user_id (user_id);

-- Notification endpoints (polled every 30-60s)
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifiable (notifiable_id, notifiable_role);
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_unread (notifiable_id, read_at, created_at DESC);

-- Candidate queries
ALTER TABLE google_sheet_data ADD INDEX IF NOT EXISTS idx_created_by_date (created_by, `Date` DESC);
ALTER TABLE google_sheet_data ADD INDEX IF NOT EXISTS idx_user_search (Name, Email_Address, Phone_Number);

-- Messages
ALTER TABLE messages ADD INDEX IF NOT EXISTS idx_recipient_id (recipient_id, created_at DESC);
ALTER TABLE messages ADD INDEX IF NOT EXISTS idx_sender_id (sender_id);
EOF
```

### Step 3: Clear Laravel Cache
```bash
cd /var/www/norloxsolutionscrm.com/wowcrm
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 4: Restart Services
```bash
sudo systemctl restart php8.3-fpm apache2
```

### Step 5: Verify Deployment
```bash
# Check site is accessible
curl -I https://norloxsolutionscrm.com/

# Check PHP-FPM status
systemctl status php8.3-fpm

# Check Apache status
systemctl status apache2

# Tail error logs
tail -100 /var/log/apache2/error.log
tail -100 /var/lib/php/sessions/*  # Check session logs
```

---

## Load Testing After Deployment

Run the load test again to verify improvements:

```bash
sudo bash /root/run_load_test.sh
```

### Expected Results:
**Before:** 
- Response time: 10,039ms average
- Requests/sec: 9.96
- CPU: 95.86

**After (target):**
- Response time: < 500ms average
- Requests/sec: > 100
- CPU: < 30%

---

## Monitoring & Troubleshooting

### Check MySQL Slow Query Log
```bash
# Enable logging (should already be on)
mysql -u root -p -e "SET GLOBAL slow_query_log = 'ON';"

# View slow queries
tail -100 /var/lib/mysql/srv1313090-slow.log
```

### Monitor Live Performance
```bash
# Watch Apache processes
watch -n1 'ps aux | grep apache | wc -l'

# Watch MySQL connections
watch -n1 'mysql -u root -p -e "SHOW PROCESSLIST;" | wc -l'

# Check load average
watch -n1 'uptime'
```

### If Performance Still Poor:

1. **Increase PHP-FPM workers** (was already increased to 150)
   ```bash
   sudo nano /etc/php/8.3/fpm/pool.d/www.conf
   # Increase pm.max_children from 150 to 200
   sudo systemctl restart php8.3-fpm
   ```

2. **Add Redis caching** for frequently accessed data
   ```bash
   sudo apt-get install redis-server
   # Configure Laravel to use Redis in .env: CACHE_DRIVER=redis
   ```

3. **Enable MySQL query cache** (if available on your MySQL version)
   ```sql
   SET GLOBAL query_cache_type = 1;
   SET GLOBAL query_cache_size = 256MB;
   ```

---

## Files Changed

### Code Changes (Already Committed)
- `app/Http/Controllers/CandidateController.php`
- `app/Http/Controllers/TimerController.php`

### Documentation
- `PERFORMANCE_FIXES.md` - Complete technical details
- `SERVER_DEPLOYMENT.md` - This file

---

## Rollback Plan

If issues occur, rollback is simple:
```bash
cd /var/www/norloxsolutionscrm.com/wowcrm
git revert 1e0a8c90f
php artisan cache:clear
sudo systemctl restart php8.3-fpm apache2
```

But indexes created on the database will remain (they're beneficial regardless).

---

## Questions?

Check the detailed technical guide in `PERFORMANCE_FIXES.md` for:
- Explanation of N+1 query problem
- Query optimization recommendations
- Caching strategies
- Additional bottleneck analysis

---

## Summary

The code changes eliminate the root cause of the CPU overload: **N+1 database queries**. 
With 100 concurrent users and requests every 30 seconds, these queries were creating 
thousands of database operations per minute.

After deployment + database indexes, the system should handle 100+ concurrent users 
with response times < 500ms and CPU usage < 30%.
