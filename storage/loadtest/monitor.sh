#!/bin/bash
PROJ_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
OUT="$PROJ_DIR/storage/loadtest/results/stats.csv"
LOGFILE="$PROJ_DIR/storage/loadtest/logs/monitor.log"

echo "TIMESTAMP,CPU_USER,CPU_SYSTEM,CPU_IOWAIT,MEM_USED_MB,MEM_AVAILABLE_MB,MEM_PERCENT,MYSQL_CONNECTIONS,PHP_FPM_PROCESSES,REDIS_CLIENTS,REDIS_OPS_SEC,TCP_ESTABLISHED" > "$OUT"
echo "[$(date)] Monitoring started, output to $OUT" > "$LOGFILE"

PREV_READ=0
PREV_WRITE=0

while true; do
    TS=$(date '+%Y-%m-%d %H:%M:%S')
    
    # CPU stats
    CPU=$(head -1 /proc/stat)
    CPU_USER=$(echo $CPU | awk '{print $2}')
    CPU_SYSTEM=$(echo $CPU | awk '{print $4}')
    CPU_IOWAIT=$(echo $CPU | awk '{print $5}')
    
    # Memory stats
    MEM=$(free -b | grep Mem)
    MEM_USED=$(echo $MEM | awk '{print int($3/1024/1024)}')
    MEM_AVAIL=$(echo $MEM | awk '{print int($7/1024/1024)}')
    MEM_TOTAL=$(echo $MEM | awk '{print int($2/1024/1024)}')
    MEM_PERCENT=$(echo "scale=1; ($MEM_USED / $MEM_TOTAL) * 100" | bc)
    
    # MySQL connections (using app DB credentials)
    MYSQL_CONN=$(mysql -u u792878158_test -p'Crm@NXS2025' -e "SHOW PROCESSLIST;" 2>/dev/null | wc -l)
    
    # PHP-FPM processes
    PHP_PROCS=$(ps aux | grep -c '[p]hp-fpm')
    
    # Redis stats
    REDIS_INFO=$(redis-cli INFO stats 2>/dev/null)
    REDIS_CLIENTS=$(echo "$REDIS_INFO" | grep connected_clients | cut -d: -f2 | tr -d '\r')
    REDIS_OPS=$(echo "$REDIS_INFO" | grep instantaneous_ops_per_sec | cut -d: -f2 | tr -d '\r')
    
    [ -z "$REDIS_CLIENTS" ] && REDIS_CLIENTS=0
    [ -z "$REDIS_OPS" ] && REDIS_OPS=0
    
    # TCP established connections
    TCP_EST=$(ss -tan | grep ESTAB | wc -l)
    
    echo "$TS,$CPU_USER,$CPU_SYSTEM,$CPU_IOWAIT,$MEM_USED,$MEM_AVAIL,$MEM_PERCENT,$MYSQL_CONN,$PHP_FPM_PROCESSES,$REDIS_CLIENTS,$REDIS_OPS,$TCP_EST" >> "$OUT"
    
    sleep 1
done
