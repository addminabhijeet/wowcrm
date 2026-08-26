#!/bin/bash
PROJ_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
RESULTS_DIR="$PROJ_DIR/storage/loadtest/results"

i=$1
BASE="https://norloxsolutionscrm.com"
JAR="$RESULTS_DIR/cookies_$i.txt"
OUT="$RESULTS_DIR/result_$i.txt"

# Get login page for CSRF token
TOKEN=$(curl -k -s -c "$JAR" "$BASE/login" 2>/dev/null | grep -oP 'name="_token" value="\K[^"]+' | head -1)

if [ -z "$TOKEN" ]; then
    echo "USER=$i ERROR=NO_TOKEN" >> "$OUT"
    exit 1
fi

# Attempt login with detailed timing
LOGIN_START=$(date +%s%N)
LOGIN_RESP=$(curl -k -s -b "$JAR" -c "$JAR" -w "|%{http_code}|%{time_total}|%{time_connect}|%{time_starttransfer}" \
  -d "_token=$TOKEN&email=loadtest.junior$i@test.local&password=LoadTest@123" \
  "$BASE/loginsubmit" 2>&1)
LOGIN_END=$(date +%s%N)
LOGIN_TIME=$(( ($LOGIN_END - $LOGIN_START) / 1000000 ))

LOGIN_STATUS=$(echo "$LOGIN_RESP" | grep -oP '\|\K[0-9]+(?=\|)' | head -1)
LOGIN_TTFB=$(echo "$LOGIN_RESP" | grep -oP '\|\K[0-9.]+(?=\|)' | tail -1)

# If login successful, fetch dashboard
DASH_STATUS="SKIPPED"
DASH_TIME=0

if [[ "$LOGIN_STATUS" == "302" ]] || [[ "$LOGIN_STATUS" == "200" ]]; then
    DASH_START=$(date +%s%N)
    DASH_RESP=$(curl -k -s -b "$JAR" -w "|%{http_code}|%{time_total}|%{time_starttransfer}" \
      -o /dev/null "$BASE/dashboard/junior" 2>&1)
    DASH_END=$(date +%s%N)
    DASH_TIME=$(( ($DASH_END - $DASH_START) / 1000000 ))
    
    DASH_STATUS=$(echo "$DASH_RESP" | grep -oP '\|\K[0-9]+(?=\|)' | head -1)
    DASH_TTFB=$(echo "$DASH_RESP" | grep -oP '\|\K[0-9.]+(?=\|)' | tail -1)
fi

# Output result
echo "USER=$i LOGIN_STATUS=$LOGIN_STATUS LOGIN_TIME_MS=$LOGIN_TIME DASHBOARD_STATUS=$DASH_STATUS DASH_TIME_MS=$DASH_TIME" >> "$OUT"

# Cleanup cookie jar
rm -f "$JAR"
