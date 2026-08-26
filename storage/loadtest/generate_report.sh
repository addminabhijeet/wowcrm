#!/bin/bash
PROJ_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
RESULTS_DIR="$PROJ_DIR/storage/loadtest/results"
REPORT_FILE="$RESULTS_DIR/REPORT.txt"

{
    echo "╔════════════════════════════════════════════════════════════════════════════╗"
    echo "║                   LOAD TEST RESULTS SUMMARY                               ║"
    echo "║              100 Concurrent Junior Users - WowCRM                          ║"
    echo "╚════════════════════════════════════════════════════════════════════════════╝"
    echo ""
    echo "Generated: $(date)"
    echo ""
    
    # HTTP RESPONSE RESULTS
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo "  HTTP RESPONSES"
    echo "═══════════════════════════════════════════════════════════════════════════"
    
    SUCCESSFUL=$(grep -c 'LOGIN_STATUS=302\|LOGIN_STATUS=200' "$RESULTS_DIR/combined_results.txt" 2>/dev/null || echo 0)
    FAILED=$(grep -c 'LOGIN_STATUS=4\|LOGIN_STATUS=5\|ERROR=' "$RESULTS_DIR/combined_results.txt" 2>/dev/null || echo 0)
    TOTAL=100
    SUCCESS_RATE=$((($SUCCESSFUL * 100) / $TOTAL))
    
    echo "  Successful logins:  $SUCCESSFUL / $TOTAL ($SUCCESS_RATE%)"
    echo "  Failed logins:      $FAILED / $TOTAL"
    echo ""
    
    # LOGIN RESPONSE TIMES
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo "  LOGIN RESPONSE TIMES (milliseconds)"
    echo "═══════════════════════════════════════════════════════════════════════════"
    
    grep 'LOGIN_TIME_MS' "$RESULTS_DIR/combined_results.txt" | grep -oP 'LOGIN_TIME_MS=\K[0-9]+' > /tmp/login_times.txt
    
    if [ -s /tmp/login_times.txt ]; then
        LOGIN_FASTEST=$(sort -n /tmp/login_times.txt | head -1)
        LOGIN_SLOWEST=$(sort -rn /tmp/login_times.txt | head -1)
        LOGIN_AVG=$(awk '{sum+=$1; count++} END {print int(sum/count)}' /tmp/login_times.txt)
        LOGIN_MEDIAN=$(sort -n /tmp/login_times.txt | awk 'NR%2==1{if (NR==1){median=$1} else {median=($1+median)/2}} END {print int(median)}')
        
        echo "  Fastest:            $LOGIN_FASTEST ms"
        echo "  Slowest:            $LOGIN_SLOWEST ms"
        echo "  Average:            $LOGIN_AVG ms"
        echo "  Median:             $LOGIN_MEDIAN ms"
        echo ""
        
        # Categorize response times
        UNDER_500=$(grep -c 'LOGIN_TIME_MS=[0-4][0-9][0-9]' "$RESULTS_DIR/combined_results.txt" || echo 0)
        UNDER_1000=$(grep -c 'LOGIN_TIME_MS=[0-9]\{1,4\}' "$RESULTS_DIR/combined_results.txt" | awk -v u=$UNDER_500 '{print $1}' || echo 0)
        OVER_1000=$(grep 'LOGIN_TIME_MS' "$RESULTS_DIR/combined_results.txt" | grep -oP 'LOGIN_TIME_MS=\K[0-9]+' | awk '{if ($1 > 1000) count++} END {print count}' || echo 0)
        
        echo "  Response time distribution:"
        echo "    < 500ms:   $UNDER_500"
        echo "    500-1000ms: $(($UNDER_1000 - $UNDER_500))"
        echo "    > 1000ms:  $OVER_1000"
    else
        echo "  ❌ No login data available"
    fi
    echo ""
    
    # DASHBOARD RESPONSE TIMES
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo "  DASHBOARD RESPONSE TIMES (milliseconds)"
    echo "═══════════════════════════════════════════════════════════════════════════"
    
    grep 'DASHBOARD_STATUS=200' "$RESULTS_DIR/combined_results.txt" | grep -oP 'DASH_TIME_MS=\K[0-9]+' > /tmp/dash_times.txt
    
    if [ -s /tmp/dash_times.txt ]; then
        DASH_FASTEST=$(sort -n /tmp/dash_times.txt | head -1)
        DASH_SLOWEST=$(sort -rn /tmp/dash_times.txt | head -1)
        DASH_AVG=$(awk '{sum+=$1; count++} END {print int(sum/count)}' /tmp/dash_times.txt)
        DASH_MEDIAN=$(sort -n /tmp/dash_times.txt | awk 'NR%2==1{if (NR==1){median=$1} else {median=($1+median)/2}} END {print int(median)}')
        DASH_COUNT=$(wc -l < /tmp/dash_times.txt)
        
        echo "  Successful requests: $DASH_COUNT / $TOTAL"
        echo "  Fastest:             $DASH_FASTEST ms"
        echo "  Slowest:             $DASH_SLOWEST ms"
        echo "  Average:             $DASH_AVG ms"
        echo "  Median:              $DASH_MEDIAN ms"
    else
        echo "  ❌ No dashboard data available"
    fi
    echo ""
    
    # SYSTEM METRICS
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo "  SYSTEM METRICS (Peak Load)"
    echo "═══════════════════════════════════════════════════════════════════════════"
    
    if [ -f "$RESULTS_DIR/stats.csv" ]; then
        # Skip header and process data
        tail -99 "$RESULTS_DIR/stats.csv" 2>/dev/null | {
            echo "  CPU:"
            CPU_USER_MAX=$(cut -d, -f2 | sort -rn | head -1)
            CPU_SYS_MAX=$(cut -d, -f3 | sort -rn | head -1)
            echo "    User:               $CPU_USER_MAX"
            echo "    System:             $CPU_SYS_MAX"
            echo ""
            
            echo "  Memory:"
            tail -99 "$RESULTS_DIR/stats.csv" | {
                MEM_MAX=$(cut -d, -f5 | sort -rn | head -1)
                MEM_AVAIL_MIN=$(cut -d, -f6 | sort -n | head -1)
                MEM_PERCENT_MAX=$(cut -d, -f7 | sort -rn | head -1)
                echo "    Used (peak):        $MEM_MAX MB"
                echo "    Available (min):    $MEM_AVAIL_MIN MB"
                echo "    Usage (peak):       $MEM_PERCENT_MAX%"
            }
            echo ""
            
            echo "  Database:"
            tail -99 "$RESULTS_DIR/stats.csv" | {
                MYSQL_MIN=$(cut -d, -f8 | sort -n | head -1)
                MYSQL_MAX=$(cut -d, -f8 | sort -rn | head -1)
                MYSQL_AVG=$(cut -d, -f8 | awk '{sum+=$1; count++} END {print int(sum/count)}')
                echo "    Min connections:    $MYSQL_MIN"
                echo "    Max connections:    $MYSQL_MAX"
                echo "    Avg connections:    $MYSQL_AVG"
            }
            echo ""
            
            echo "  Redis:"
            tail -99 "$RESULTS_DIR/stats.csv" | {
                REDIS_CLIENTS_MAX=$(cut -d, -f10 | sort -rn | head -1)
                REDIS_OPS_MAX=$(cut -d, -f11 | sort -rn | head -1)
                echo "    Max clients:        $REDIS_CLIENTS_MAX"
                echo "    Max ops/sec:        $REDIS_OPS_MAX"
            }
            echo ""
            
            echo "  Network:"
            tail -99 "$RESULTS_DIR/stats.csv" | {
                TCP_MAX=$(cut -d, -f12 | sort -rn | head -1)
                echo "    Max TCP connections: $TCP_MAX"
            }
        }
    else
        echo "  ⚠️  System metrics not available"
    fi
    echo ""
    
    # VERDICT
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo "  VERDICT"
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo ""
    
    if [ "$SUCCESS_RATE" -ge 95 ]; then
        echo "  ✅ EXCELLENT: $SUCCESS_RATE% success rate"
    elif [ "$SUCCESS_RATE" -ge 90 ]; then
        echo "  ✅ GOOD: $SUCCESS_RATE% success rate"
    elif [ "$SUCCESS_RATE" -ge 80 ]; then
        echo "  ⚠️  ACCEPTABLE: $SUCCESS_RATE% success rate"
    else
        echo "  ❌ POOR: $SUCCESS_RATE% success rate"
    fi
    
    if [ -f /tmp/login_times.txt ]; then
        if [ "$LOGIN_AVG" -lt 500 ]; then
            echo "  ✅ Response times excellent (avg: ${LOGIN_AVG}ms)"
        elif [ "$LOGIN_AVG" -lt 1000 ]; then
            echo "  ✅ Response times acceptable (avg: ${LOGIN_AVG}ms)"
        else
            echo "  ❌ Response times poor (avg: ${LOGIN_AVG}ms)"
        fi
    fi
    echo ""
    
    echo "═══════════════════════════════════════════════════════════════════════════"
    echo ""
    
} | tee "$REPORT_FILE"

echo "✓ Report saved: $REPORT_FILE"
