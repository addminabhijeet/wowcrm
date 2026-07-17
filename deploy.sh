#!/bin/bash

# ============================================================================
# WowCRM Performance Fix Deployment Script
# ============================================================================
# This script deploys the performance fixes in the correct order
# ============================================================================

set -e  # Exit on any error

echo "=========================================="
echo "WowCRM Performance Fix Deployment"
echo "=========================================="
echo ""

# Step 1: Verify we're on the production server
if [ ! -f /var/www/norloxsolutionscrm.com/wowcrm/artisan ]; then
    echo "❌ Error: Not in correct directory"
    echo "Must run from: /var/www/norloxsolutionscrm.com/wowcrm"
    exit 1
fi

# Step 2: Pull latest code
echo "📥 Step 1: Pulling latest code..."
cd /var/www/norloxsolutionscrm.com/wowcrm
git pull origin main
if [ $? -eq 0 ]; then
    echo "✅ Code pulled successfully"
else
    echo "❌ Failed to pull code"
    exit 1
fi
echo ""

# Step 3: Create database indexes (MOST IMPORTANT!)
echo "🗄️  Step 2: Creating critical database indexes..."
echo "⚠️  This may take 1-5 minutes depending on table sizes..."
mysql -u root -p < CRITICAL_DATABASE_INDEXES.sql
if [ $? -eq 0 ]; then
    echo "✅ Database indexes created"
else
    echo "❌ Failed to create indexes"
    exit 1
fi
echo ""

# Step 4: Clear Laravel cache
echo "🧹 Step 3: Clearing Laravel cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
if [ $? -eq 0 ]; then
    echo "✅ Cache cleared"
else
    echo "❌ Failed to clear cache"
    exit 1
fi
echo ""

# Step 5: Restart services
echo "🔄 Step 4: Restarting services..."
sudo systemctl restart php8.3-fpm
if [ $? -eq 0 ]; then
    echo "✅ PHP-FPM restarted"
else
    echo "❌ Failed to restart PHP-FPM"
    exit 1
fi

sudo systemctl restart apache2
if [ $? -eq 0 ]; then
    echo "✅ Apache restarted"
else
    echo "❌ Failed to restart Apache"
    exit 1
fi
echo ""

# Step 6: Verify services are running
echo "✔️  Step 5: Verifying services..."
systemctl is-active --quiet php8.3-fpm && echo "✅ PHP-FPM running" || echo "❌ PHP-FPM not running"
systemctl is-active --quiet apache2 && echo "✅ Apache running" || echo "❌ Apache not running"
systemctl is-active --quiet mysql && echo "✅ MySQL running" || echo "❌ MySQL not running"
echo ""

# Step 7: Test website accessibility
echo "🌐 Step 6: Testing website..."
if curl -s -I https://norloxsolutionscrm.com/ | grep -q "200\|301\|302"; then
    echo "✅ Website is accessible"
else
    echo "❌ Website is not accessible"
    exit 1
fi
echo ""

echo "=========================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Run load test: sudo bash /root/run_load_test.sh"
echo "2. Check metrics for improvements"
echo "3. Expected: Response time < 500ms, Throughput > 50 req/sec, CPU < 30%"
echo ""
echo "If issues occur:"
echo "  - Check logs: tail -100 /var/log/apache2/error.log"
echo "  - Check MySQL: tail -100 /var/lib/mysql/srv1313090-slow.log"
echo "  - Rollback: git revert HEAD --no-edit && git pull"
echo ""
