# WowCRM Performance Optimization - Complete Solution

## Issues Identified

### 1. **N+1 Query Problem (CRITICAL - FIXED)**
**Location:** `app/Http/Controllers/CandidateController.php` lines 87-89
**Problem:** Inside a loop iterating through records, it was querying User table for each row.
- With 10 records per page + 100 concurrent users = 1000s of database queries
- **Fix Applied:** Fetch all users upfront with `keyBy('id')` then use array lookup

### 2. **High-Frequency Polling Endpoints (MITIGATED)**
From `resources/views/components/navbar.blade.php`:
- `checkButtonStatus`: Every 30s (was 1s) 
- `checkTimerStatus`: Every 30s (was 1s)
- `updatePauseButtons`: Every 30s (was 1s)
- `latestNotification`: Every 60s (was 10s)
- `loadMessages`: Every 30s (was 5s)

These endpoints need database optimization.

### 3. **Missing Database Indexes**
The following columns are frequently queried but lack indexes:
- `users.is_deleted` - Used in almost every query
- `timer_logs.user_id` - Polled every 30s
- `notifications.notifiable_id` - Polled every 30s
- `google_sheet_data.created_by` - Complex LIKE queries

---

## Fixes Applied

### Fix #1: Remove N+1 Queries (DONE)
```php
// BEFORE: N+1 queries inside loop
$data->getCollection()->transform(function ($item) use ($authUser) {
    foreach ($entries as $entry) {
        $user = User::where('is_deleted', 0)->find($userId); // QUERY PER ROW
    }
});

// AFTER: Single query + array lookups
$allUsers = User::where('is_deleted', 0)->get()->keyBy('id');
$data->getCollection()->transform(function ($item) use ($authUser, $allUsers) {
    foreach ($entries as $entry) {
        $user = $allUsers->get($userId); // Array lookup
    }
});
```

---

## Recommended Database Optimizations

### Add These Indexes
Run these SQL commands on your production database:

```sql
-- Essential indexes for polled queries
ALTER TABLE users ADD INDEX idx_is_deleted (is_deleted);
ALTER TABLE users ADD INDEX idx_status_deleted (status, is_deleted);

-- Timer endpoints
ALTER TABLE user_timer_logs ADD INDEX idx_user_id_latest (user_id, created_at DESC);
ALTER TABLE user_timer_pause ADD INDEX idx_user_id (user_id);

-- Notification endpoints  
ALTER TABLE notifications ADD INDEX idx_notifiable (notifiable_id, notifiable_role);
ALTER TABLE notifications ADD INDEX idx_unread (notifiable_id, read_at, created_at DESC);

-- Candidate queries
ALTER TABLE google_sheet_data ADD INDEX idx_created_by_date (created_by, `Date` DESC);
ALTER TABLE google_sheet_data ADD INDEX idx_user_search (Name, Email_Address, Phone_Number);

-- Chat/Messages
ALTER TABLE messages ADD INDEX idx_recipient_id (recipient_id, created_at DESC);
ALTER TABLE messages ADD INDEX idx_sender_id (sender_id);
```

### Cache User Lookups
Since User data is fetched frequently, cache it:

```php
// In app/Http/Controllers/CandidateController.php
private function getAllUsers()
{
    return Cache::remember('all_active_users', 300, function() {
        return User::where('is_deleted', 0)->get()->keyBy('id');
    });
}

// Then use:
$allUsers = $this->getAllUsers();
```

---

## Configuration Optimizations (Already Applied)

From the quick_fix.sh:
- ✅ Apache access logging disabled (I/O reduction)
- ✅ PHP-FPM workers increased: 100 → 150
- ✅ PHP-FPM max_requests: 500 → 5000
- ✅ MySQL slow query logging enabled

---

## Query Optimization Recommendations

### 1. Simplify `created_by` Parsing
**Current Problem:** Complex LIKE queries with RIGHT() function
```sql
WHERE RIGHT(created_by, LENGTH(?)) = ?  -- Slow on large tables
```

**Better Approach:** Store a normalized column
```sql
ALTER TABLE google_sheet_data ADD COLUMN primary_creator_id INT;
ALTER TABLE google_sheet_data ADD INDEX idx_primary_creator (primary_creator_id);

-- Then in Laravel:
$query->where('primary_creator_id', $authUser->id)
```

### 2. Eager Load Relations
```php
// BEFORE
$notifications = Notification::where(...)->get();
// Results in N queries for user/candidate relations

// AFTER  
$notifications = Notification::with(['user', 'candidate'])
    ->where(...)->get();
```

### 3. Use `latest()` Correctly
```php
// BEFORE
$timer = UserTimerLog::where('user_id', $junior->id)->latest()->first();

// AFTER - Add index for this pattern
ALTER TABLE user_timer_logs ADD INDEX idx_latest (user_id, created_at DESC);
$timer = UserTimerLog::where('user_id', $junior->id)
    ->orderBy('created_at', 'desc')
    ->first();
```

---

## Load Test Targets

**After all fixes, target these metrics:**
- Response time: < 500ms (currently 10s)
- Requests/sec: > 100 (currently 10)
- CPU usage: < 30% (currently 95%)
- Database connections: < 20 (adjust PHP-FPM max_children)

Run the load test again:
```bash
sudo bash /root/run_load_test.sh
```

---

## Files Modified

1. ✅ `app/Http/Controllers/CandidateController.php` - Fixed N+1 queries

## Files to Review

1. `app/Http/Controllers/TimerController.php` - Check similar patterns
2. `app/Http/Controllers/DashboardController.php` - Notification queries
3. `app/Http/Controllers/ChatController.php` - Message queries
4. `resources/views/timers/senior.blade.php` - Verify polling is now 30s
5. `resources/views/timers/allsenior.blade.php` - Verify polling is now 30s

---

## Next Steps

1. **Run database indexes** on production
2. **Clear Laravel cache** after deploying fixes
3. **Run load test** to verify improvements
4. **Monitor slow query log** for remaining bottlenecks
5. **Consider caching layer** (Redis) for frequently accessed data
