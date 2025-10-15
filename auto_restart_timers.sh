#!/bin/bash

# ===== Configuration =====
PROJECT_PATH="/home/u235777426/public_html"
PHP_PATH="/usr/bin/php"
LOG_FILE="$PROJECT_PATH/storage/logs/timer_decrement.log"

# ===== Infinite Loop =====
while true; do
    echo "[$(date)] Starting timers:decrement process..." | tee -a $LOG_FILE

    cd $PROJECT_PATH || exit
    $PHP_PATH artisan timers:decrement >> $LOG_FILE 2>&1

    echo "[$(date)] Process stopped or crashed. Restarting in 5 seconds..." | tee -a $LOG_FILE
    sleep 5
done
